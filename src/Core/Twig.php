<?php namespace RescueCore\Core;

class Twig
{
    static private $twig = null;

    private function __construct()
    {
    }

    public static function set($templatePath)
    {
        $loader = new \Twig_Loader_Filesystem($templatePath);
        $twig   = new \Twig_Environment($loader, [
//    'cache' => DOCUMENT_ROOT . '/cache/twig',
        ]);
        $twig->addFunction(new \Twig_SimpleFunction('partial', function ($name, $vars = []) use ($twig) {
            $template = $twig->load('partials/' . $name . '.twig');

            return $template->render($vars);
        }));

        static::$twig = $twig;
    }

    public static function get()
    {
        if (! isset(static::$twig)) {
            throw new \Exception('Not initialized');
        }

        return static::$twig;
    }
}
