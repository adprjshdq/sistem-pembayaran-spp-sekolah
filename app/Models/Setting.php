<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    /** @var array<string, string|null> */
    protected static array $cache = [];

    public static function get(string $key, ?string $default = null): ?string
    {
        if (! array_key_exists($key, static::$cache)) {
            static::$cache[$key] = static::query()->where('key', $key)->value('value');
        }

        return static::$cache[$key] ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);

        static::$cache[$key] = $value;
    }

    public static function flushCache(): void
    {
        static::$cache = [];
    }
}
