<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsSetting extends Model
{
    use HasFactory;

    protected $table = 'cms_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Retrieve a setting value by key.
     */
    public static function getByKey(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) return $default;

        $val = $setting->getRawOriginal('value');
        if ($val === null) return $default;

        $decoded = json_decode($val, true);
        return ($decoded !== null && json_last_error() === JSON_ERROR_NONE) ? $decoded : $val;
    }

    /**
     * Store a setting value by key.
     */
    public static function setByKey(string $key, $value, string $group = 'general')
    {
        static::ensureTextColumn();

        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => (string)$value, 'group' => $group]
        );
    }

    /**
     * Helper to ensure database column type is LONGTEXT instead of JSON in MySQL.
     */
    protected static function ensureTextColumn(): void
    {
        static $checked = false;
        if ($checked) return;
        $checked = true;

        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE cms_settings MODIFY value LONGTEXT NULL');
        } catch (\Throwable $e) {
            // Column already altered or DB migration handled
        }
    }
}

