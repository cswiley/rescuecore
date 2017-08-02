<?php namespace RescueCore\Core;

use RescueCore\Controllers;

class Route {
    static function match($pattern, $callback)
    {
        $match = Uri::match($pattern);
        if ($match !== false) {
            $callback($match[1], $match[0]);
        }
    }

}
