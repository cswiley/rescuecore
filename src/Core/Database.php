<?php namespace RescueCore\Core;

use ORM;
use Model;

Class Database
{
    static function init($opts)
    {
        $name  = env('DB.NAME');
        $host  = env('DB.HOST');
        $un    = env('DB.USERNAME');
        $pw    = env('DB.PASSWORD');
        $debug = env("DEBUG", false);

        ORM::configure('mysql:host=' . $host . ';dbname=' . $name);
        ORM::configure('username', $un);
        ORM::configure('password', $pw);
        ORM::configure('logging', $debug);

        if (!empty($opts['auto_prefix_models'])) {
            Model::$auto_prefix_models = $opts['auto_prefix_models'];
        }
    }
}

