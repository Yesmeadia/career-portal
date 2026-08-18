<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\CmsService;
use App\Models\CmsSetting;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsController extends Controller
{
    public function __construct(
        protected CmsService $cmsService
    ) {}

    public function index()
    {
        $rawCms = $this->cmsService->getHomepageContent();

        // Convert stored relative paths to full URLs for the view
        $cms = $rawCms;
        if (!empty($cms['site_logo'])) {
            $cms['site_logo'] = $this->resolveMediaUrl($cms['site_logo']);
        }
        if (!empty($cms['site_favicon'])) {
            $cms['site_favicon'] = $this->resolveMediaUrl($cms['site_favicon']);
        }

        $emailTemplates = EmailTemplate::all();

        $mailConfig = [
            'mail_mailer'       => env('MAIL_MAILER', config('mail.default', 'smtp')),
            'mail_host'         => env('MAIL_HOST', config('mail.mailers.smtp.host', '127.0.0.1')),
            'mail_port'         => env('MAIL_PORT', config('mail.mailers.smtp.port', '2525')),
            'mail_username'     => env('MAIL_USERNAME', config('mail.mailers.smtp.username', '')),
            'mail_password'     => env('MAIL_PASSWORD', config('mail.mailers.smtp.password', '')),
            'mail_encryption'   => env('MAIL_ENCRYPTION', config('mail.mailers.smtp.encryption', 'tls')),
            'mail_from_address' => env('MAIL_FROM_ADDRESS', config('mail.from.address', 'admin@school.edu')),
            'mail_from_name'    => env('MAIL_FROM_NAME', config('mail.from.name', 'School Portal')),
        ];

        return view('superadmin.cms.index', compact('cms', 'emailTemplates', 'mailConfig'));
    }

    public function updateHomepage(Request $request)
    {
        $request->validate([
            'site_name'          => 'nullable|string|max:255',
            'site_logo_file'     => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'site_favicon_file'  => 'nullable|image|mimes:jpeg,png,jpg,ico,svg,webp|max:1024',
        ]);

        // Fields to skip — handled separately or should not be saved as-is
        $skipFields = ['_token', 'site_logo_file', 'site_favicon_file', 'site_logo', 'site_favicon'];
        $settings = $request->except($skipFields);

        // Handle Site Logo Upload — store directly in public/branding/
        if ($request->hasFile('site_logo_file')) {
            $oldLogo = CmsSetting::getByKey('site_logo');
            if ($oldLogo && !str_starts_with($oldLogo, 'http')) {
                Storage::disk('branding')->delete(basename($oldLogo));
            }
            $path = $request->file('site_logo_file')->store('', 'branding');
            $settings['site_logo'] = $path;
        } elseif ($request->filled('site_logo')) {
            $settings['site_logo'] = $request->input('site_logo');
        }

        // Handle Site Favicon Upload — store directly in public/branding/
        if ($request->hasFile('site_favicon_file')) {
            $oldFavicon = CmsSetting::getByKey('site_favicon');
            if ($oldFavicon && !str_starts_with($oldFavicon, 'http')) {
                Storage::disk('branding')->delete(basename($oldFavicon));
            }
            $path = $request->file('site_favicon_file')->store('', 'branding');
            $settings['site_favicon'] = $path;
        } elseif ($request->filled('site_favicon')) {
            $settings['site_favicon'] = $request->input('site_favicon');
        }

        // Save each setting — skip null AND empty-string values to avoid erasing existing data
        foreach ($settings as $key => $val) {
            if ($val !== null && $val !== '') {
                $this->cmsService->updateSetting($key, $val, 'general');
            }
        }

        return redirect()->back()->with('success', 'Site Settings updated successfully.');
    }

    public function updateMailConfig(Request $request)
    {
        $validated = $request->validate([
            'mail_mailer'       => 'required|string|max:50',
            'mail_host'         => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('mail_mailer') === 'smtp' && empty($value)) {
                        $fail('The SMTP Host is required when using SMTP.');
                    }
                    if (str_contains((string) $value, '@')) {
                        $fail('The SMTP Host must be a server hostname (e.g. smtp.hostinger.com or mail.ruihss.in), not an email address.');
                    }
                },
            ],
            'mail_port'         => 'nullable|numeric',
            'mail_username'     => 'nullable|string|max:255',
            'mail_password'     => 'nullable|string|max:255',
            'mail_encryption'   => 'nullable|string|max:50',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name'    => 'required|string|max:255',
        ]);

        // Write directly to .env file if it exists
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);

            $keyMappings = [
                'MAIL_MAILER'       => $validated['mail_mailer'],
                'MAIL_HOST'         => $validated['mail_host'],
                'MAIL_PORT'         => $validated['mail_port'],
                'MAIL_USERNAME'     => $validated['mail_username'] ? '"' . str_replace('"', '\"', $validated['mail_username']) . '"' : '',
                'MAIL_PASSWORD'     => $validated['mail_password'] ? '"' . str_replace('"', '\"', $validated['mail_password']) . '"' : '',
                'MAIL_ENCRYPTION'   => $validated['mail_encryption'] ?? '',
                'MAIL_FROM_ADDRESS' => '"' . str_replace('"', '\"', $validated['mail_from_address']) . '"',
                'MAIL_FROM_NAME'    => '"' . str_replace('"', '\"', $validated['mail_from_name']) . '"',
            ];

            foreach ($keyMappings as $key => $val) {
                if (preg_match("/^{$key}=.*/m", $envContent)) {
                    $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$val}", $envContent);
                } else {
                    $envContent .= "\n{$key}={$val}";
                }
            }

            file_put_contents($envPath, $envContent);
        }

        // Also save to CmsSetting database table for persistence
        foreach ($validated as $key => $val) {
            if ($val !== null) {
                $this->cmsService->updateSetting($key, $val, 'mail');
            }
        }

        return redirect()->back()->with('success', 'Mail configuration updated successfully and saved to .env file.');
    }

    public function updateEmailTemplate(Request $request, EmailTemplate $template)
    {
        $validated = $request->validate([
            'subject'   => 'required|string|max:255',
            'body'      => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $template->update($validated);
        return redirect()->back()->with('success', 'Email Template updated successfully.');
    }

    /**
     * Resolve a stored logo/favicon value to a full displayable URL.
     */
    private function resolveMediaUrl(string $value): string
    {
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return Storage::disk('branding')->url($value);
    }
}
