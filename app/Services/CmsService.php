<?php

namespace App\Services;

use App\Models\CmsSetting;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Storage;

class CmsService
{
    public function getSiteSettings(): array
    {
        $siteName = CmsSetting::getByKey('site_name') ?: config('app.name', 'School Careers');

        return [
            'site_name'    => $siteName,
            'site_logo'    => $this->resolveMediaUrl(CmsSetting::getByKey('site_logo', null)),
            'site_favicon' => $this->resolveMediaUrl(CmsSetting::getByKey('site_favicon', null)),
        ];
    }

    public function getHomepageContent(): array
    {
        $siteName = CmsSetting::getByKey('site_name') ?: config('app.name', 'School Careers');

        return [
            'site_name'      => $siteName,
            'site_logo'      => $this->resolveMediaUrl(CmsSetting::getByKey('site_logo', null)),
            'site_favicon'   => $this->resolveMediaUrl(CmsSetting::getByKey('site_favicon', null)),
            'hero_title'     => CmsSetting::getByKey('hero_title', 'Build what\'s next in education'),
            'hero_subtitle'  => CmsSetting::getByKey('hero_subtitle', 'Explore teaching, leadership, and administrative opportunities across top educational institutions.'),
            'hero_badge'     => CmsSetting::getByKey('hero_badge', 'Official Institutional Career Portal'),
            'about_title'    => CmsSetting::getByKey('about_title', 'Empowering Educational Excellence'),
            'about_content'  => CmsSetting::getByKey('about_content', 'Our network of schools offers unprecedented growth, competitive benefits, and a culture of collaborative learning.'),
            'stats_teachers'     => CmsSetting::getByKey('stats_teachers', '500+'),
            'stats_schools'      => CmsSetting::getByKey('stats_schools', '25+'),
            'stats_hired'        => CmsSetting::getByKey('stats_hired', '1,200+'),
            'stats_satisfaction' => CmsSetting::getByKey('stats_satisfaction', '98%'),
        ];
    }

    public function updateSetting(string $key, $value, string $group = 'general'): CmsSetting
    {
        return CmsSetting::setByKey($key, $value, $group);
    }

    /**
     * Resolve a stored logo/favicon value to a full displayable URL.
     * Relative filenames are served from public/branding/ via the 'branding' disk.
     */
    private function resolveMediaUrl(?string $value): ?string
    {
        if (empty($value)) return null;

        // Already a full HTTP/HTTPS URL — return as-is
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        // Relative filename (e.g. "abc123.png") stored by the branding disk
        return Storage::disk('branding')->url($value);
    }
}
