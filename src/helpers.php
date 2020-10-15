<?php

if (!function_exists('base_path')) {
    function base_path(string $path = null) {
        $base = dirname(__DIR__);
        if (!$path) {
            return $base;
        }

        return $base . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
    }
}
