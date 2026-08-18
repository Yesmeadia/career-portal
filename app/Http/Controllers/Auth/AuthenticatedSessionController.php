<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // ── 1. Cloudflare Turnstile Verification ──────────────────────────────
        $turnstileSecret = config('services.turnstile.secret_key');

        if ($turnstileSecret) {
            $isTestKey = str_contains($turnstileSecret, '1x00000000000');

            try {
                $token = $request->input('cf-turnstile-response');
                $response = Http::timeout(3)->asForm()->post(
                    'https://challenges.cloudflare.com/turnstile/v0/siteverify',
                    [
                        'secret'   => $turnstileSecret,
                        'response' => $token,
                        'remoteip' => $request->ip(),
                    ]
                );

                if (! $response->successful() || ! ($response->json('success') === true)) {
                    if (! ($isTestKey || app()->environment('local'))) {
                        RateLimiter::hit('login|ip|' . $request->ip(), 600);
                        return back()->withErrors([
                            'cf-turnstile-response' => 'Security verification failed. Please complete the CAPTCHA and try again.',
                        ])->onlyInput('email');
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Turnstile verification cURL request failed: ' . $e->getMessage());

                if (! ($isTestKey || app()->environment('local'))) {
                    return back()->withErrors([
                        'cf-turnstile-response' => 'Security verification service temporarily unreachable. Please try again.',
                    ])->onlyInput('email');
                }
            }
        }

        // ── 2. Authenticate (rate-limiting lives inside LoginRequest) ─────────
        $request->authenticate();
        $request->session()->regenerate();

        // ── 3. Route to correct dashboard ─────────────────────────────────────
        $user = Auth::user();
        $superAdminRole  = Role::firstOrCreate(['name' => 'Super Admin',  'guard_name' => 'web']);
        $schoolAdminRole = Role::firstOrCreate(['name' => 'School Admin', 'guard_name' => 'web']);

        if ($user->hasRole('Super Admin') || $user->id === 1 || empty($user->school_id)) {
            if (! $user->hasRole('Super Admin')) {
                $user->assignRole($superAdminRole);
            }
            return redirect()->route('superadmin.dashboard');
        }

        if ($user->hasRole('School Admin') || $user->school_id) {
            if (! $user->hasRole('School Admin')) {
                $user->assignRole($schoolAdminRole);
            }
            return redirect()->route('schooladmin.dashboard');
        }

        return redirect()->route('home');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
