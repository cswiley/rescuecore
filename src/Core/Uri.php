<?php namespace RescueCore\Core;

class Uri {
    private static $path;

    private static function init()
    {
        list($route) = explode("?", $_SERVER["REQUEST_URI"]);
        static::$path = $route;
    }

    static function get()
    {
        if (empty($path)) {
            static::init();
        }
        return static::$path;
    }

    static function segments($num = false)
    {
        $segments = static::parseSegments(static::get());

        if (! $num) {
            return $segments;
        }

        if (isset($segments[$num])) {
            return $segments[$num];
        }

        return '';
    }

    private static function parseSegments($path) {
        return array_filter(explode('/', $path));
    }

    static function match($pattern)
    {
        $filtered = str_replace('/', '\/', $pattern);
        if (preg_match("/^$filtered$/", static::get(), $matches)) {
            if (count($matches) === 1) {
                return [
                    '',
                    []
                ];
            }
            return [
                $matches[1],
                static::parseSegments($matches[1]),
            ];
        }
        return false;
    }
}
