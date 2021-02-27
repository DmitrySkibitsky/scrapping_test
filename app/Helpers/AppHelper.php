<?php

namespace App\Helpers;

class AppHelper
{
    public static function publicPath($path = ''): string
    {
        return env('PUBLIC_PATH', base_path('public')) . ($path ? '/' . $path : $path);
    }
}
