<?php namespace RescueCore\Core;

class Bootstrap
{
    static function init($opts = [])
    {
        session_start();

        require_once "helpers.php";
        require_once "utils.php";
        require_once "constants.php";
        require_once "environment.php";
        require_once "config.php";
        require_once "site.php";
        require_once "scaffolds.php";

        if (config('TWIG_TEMPLATES')) {
            Twig::set(DOCUMENT_ROOT . '/' . config('TWIG_TEMPLATES'));
        }

        Database::init($opts);

        if (env('DEBUG')) {
            ini_set('error_reporting', E_ALL);
        } else {
            ini_set('error_reporting', 0);
        }


        if (env('MAINTENANCE')) {
            if (!preg_match('/^test\./', $_SERVER['HTTP_HOST'])) {
                require_once 'maintenance.php';
                exit;
            };
        }
    }
}


