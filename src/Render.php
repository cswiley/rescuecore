<?php

use RescueCore\Core\Twig;

class Render
{
    static function view($path, $data = [], $return = false)
    {
        $filePath = $path;
        // Add php extension when no extension exists
        if (!preg_match('/\.[a-z_\-0-9]+$/i', $filePath)) {
            $filePath = $path . '.php';
        }

        $fullPath = VIEWS_PATH . '/' . $filePath;
        $data     = empty($data) ? [] : $data;

        $data = static::addGenericPageData($data);

        ob_start(); // turn on output buffering
        extract($data);
        include($fullPath);
        $content = ob_get_contents(); // get the contents of the output buffer
        ob_end_clean();
        if ($return) {
            return $content;
        }
        echo $content;

        return true;
    }

    private static function addGenericPageData($data)
    {
        $data                = static::addConfigData($data);
        $data                = static::addSiteData($data);
        $data                = static::addEnvData($data);
        $data['current_url'] = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        return $data;
    }

    private static function twig()
    {
        return $GLOBALS['TWIG'];
    }

    static function code($title, $html = '', $code = '', $comment = '')
    {
        if ($code === '') {
            $code = $html;
        }

        $showCode = empty($html) && !empty($code);

        return static::partial('styleguide/code', [
            'title'    => $title,
            'code'     => $code,
            'html'     => $html,
            'showCode' => $showCode,
            'comment'  => $comment
        ]);
    }

    static function codePartial($title, $file, $data = null, $return = false)
    {
        $html = static::partial($file, $data, true);

        return static::partial('styleguide/code', [
            'title' => $title,
            'html'  => $html
        ], $return);

    }

    static function partial($file, $data = null, $return = false)
    {
        if (empty($data)) {
            $data = [];
        }
        $data     = static::addGenericPageData($data);
        $file     = 'partials/' . $file;
        $ext      = '.twig';
        $template = Twig::get(TWIG_PATH)->load($file . $ext);
        $html     = $template->render($data);
        if ($return) {
            return $html;
        }
        echo $html;
    }

    private static function concat()
    {
        $args = func_get_args();
        implode('', $args);
    }

    static function addScript($key, $script)
    {
        if (empty($GLOBALS['scripts'])) {
            $GLOBALS['scripts'] = [];
        }
        $GLOBALS['scripts'][$key] = $script;
    }

    static function getScripts()
    {

        return !empty($GLOBALS['scripts']) ? $GLOBALS['scripts'] : null;
    }

    private static function getM()
    {
        return '?m=' . uniqid();
    }

    static function assets($path, $cacheBust = false)
    {
        $version = $cacheBust ? static::getM() : '';

        return ASSETS . '/' . $path . $version;
    }

    private static function getSiteData()
    {
        $fullPath = DATA_PATH . '/site.yml';

        return parseYaml($fullPath);
    }

    private static function getPageData($path)
    {
        $fullPath = RESOURCES_PATH . '/data/' . $path . '.json';
        if (file_exists($fullPath)) {
            return json_decode(file_get_contents($fullPath), true);
        }

        return null;
    }

    private static function buildAttributes($attr = [])
    {
        $res = [];
        foreach ($attr as $key => $value) {
            $res[] = $key . '="' . $value . '"';
        }

        return implode(' ', $res);

    }

    static function linkTo($src, $title = '', $attributes = [])
    {
        return implode('', [
            '<a href="' . $src . '" ' . static::buildAttributes($attributes) . '>',
            $title,
            '</a>'
        ]);
    }

    static function toJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private static function addConfigData($data = [])
    {
        $globalData = config();
        if (!empty($globalData)) {
            $data = array_merge($data, ['config' => $globalData]);
        }

        return $data;
    }

    private static function addEnvData($data = [])
    {
        $globalData = env();

        if (!empty($globalData)) {
            unset($globalData['DB']);
            $data = array_merge($data, ['env' => $globalData]);
        }

        return $data;

    }

    private static function addSiteData($data = [])
    {
        $globalData = site();
        if (!empty($globalData)) {
            $data = array_merge($data, ['site' => $globalData]);
        }

        return $data;
    }

    private static function addFooterData($data = [])
    {
        $data = array_merge($data, ['scripts' => static::getScripts()]);

        return $data;
    }

    static function pageView($path, $data = [], $return = false)
    {
        $data["jsController"] = $path;

        $data = array_merge($data, [
            'pageName' => str_replace('/', '__', $path)
        ]);

        $pageData = static::getPageData($path);
        if (!empty($pageData)) {
            $data = array_merge($pageData, $data);
        }

        $data       = static::addConfigData($data);
        $data       = static::addSiteData($data);
        $data       = static::addEnvData($data);
        $html       = static::partial("header", $data, true);
        $html       .= static::view($path, $data, true);
        $footerData = static::addFooterData($data);
        $html       .= static::partial("footer", $footerData, true);

        if (!$return) {
            echo $html;
        }

        return $html;
    }

    static function adminView($path, $data = [], $return = false)
    {
        $data["jsController"] = $path;

        $data = array_merge($data, [
            'pageName' => 'admin ' . str_replace('/', '__', $path)
        ]);

        $pageData = static::getPageData($path);
        if (!empty($pageData)) {
            $data = array_merge($pageData, $data);
        }

        $data = static::addGenericPageData($data);

        $html = static::partial("admin/header", $data, true);
        $html .= static::view($path, $data, true);

        $footerData = static::addFooterData($data);
        $html       .= static::partial("admin/footer", $footerData, true);

        if (!$return) {
            echo $html;
        }

        return $html;
    }

}
