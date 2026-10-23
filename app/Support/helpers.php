<?php

use App\Models\Setting;

if (! function_exists('rupiah')) {
    function rupiah(int|float|string|null $amount): string
    {
        return 'Rp '.number_format((int) $amount, 0, ',', '.');
    }
}

if (! function_exists('school_setting')) {
    function school_setting(string $key, ?string $default = null): ?string
    {
        return Setting::get($key, $default);
    }
}
