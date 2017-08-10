<?php

use Symfony\Component\Yaml\Yaml;

function getJson($path, $assoc = true)
{
    $string = file_get_contents($path);
    return json_decode($string, $assoc);
}

function o($string, $return = false)
{
    $res = htmlEntities($string, ENT_QUOTES);
    if ($return) {
        return $res;
    }
    echo $res;
    return null;
}

function o_uc($string, $return = false)
{
    $string = ucwords($string);
    return o($string, $return);
}

function sqlDate($timePhrase = '', $format = 'Y-m-d H:i:s')
{
    return (!!$timePhrase) ? date($format, strtotime($timePhrase)) : date($format);
}

function activeClass($path)
{
    list($route) = explode("?", $_SERVER["REQUEST_URI"]);
    if ($path === $route) {
        return 'active';

    }
    return '';
}

function parseYaml($path)
{
    if (file_exists($path)) {
        return Yaml::parse(file_get_contents($path));
    }

    return null;
}

function post($key = false, $default = false)
{
    $data = $_POST;

    if (empty($_POST)) {
        $data = json_decode(file_get_contents('php://input'), true);
    }

    if (! $key) {
        return $data;
    }

    return empty($data[$key]) ? $default : $data[$key];
}

function get($key = false, $default = false)
{
    $data = $_GET;

    if (!$key) {
        return $data;
    }

    return empty($data[$key]) ? $default : $data[$key];
}

function request($key = false, $default = false)
{
    $data = post();

    if (empty($data)) {
        $data = get();
    }

    if (!$key) {
        return $data;
    }

    return empty($data[$key]) ? $default : $data[$key];
}

function url_origin($use_forwarded_host = false)
{
    $s        = $_SERVER;
    $ssl      = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
    $sp       = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port     = $s['SERVER_PORT'];
    $port     = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
    $host     = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host     = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function full_url($use_forwarded_host = false)
{
    $s = $_SERVER;

    return url_origin($use_forwarded_host) . $s['REQUEST_URI'];
}

function site_url($path = '')
{
    if (empty($path)) {
        return url_origin();
    }
    return url_origin() . '/' . $path;
}

function redirect($url = '')
{
    if (empty($url)) {
        $url = full_url();
    }
    header("Location: " . $url);
    exit();
}

if (!function_exists('dbg')) {
    function dbg()
    {
        $args = func_get_args();

        foreach ($args as $arg) {
            var_dump($arg);
        }
    }
}

if (! function_exists('dd')) {
    function dd()
    {
        $args = func_get_args();

        foreach ($args as $arg) {
            var_dump($arg);
        }

        exit;
    }
}







