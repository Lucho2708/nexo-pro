<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class GlobalSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("global_setting_{$key}", function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            if (!$setting) return $default;
            
            // Auto decoding if it looks like boolean/number/json
            $val = $setting->value;
            if ($val === 'true') return true;
            if ($val === 'false') return false;
            if (is_numeric($val)) return $val + 0;
            
            return $val;
        });
    }

    public static function set(string $key, $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        Cache::forget("global_setting_{$key}");
    }
}
