<?php namespace RescueCore\Core;

use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;
use \Render;

class StyleGuide {

    private $dirPath;

    function __construct($dirPath)
    {
        $this->dirPath = $dirPath;
    }

    static function view($path, $data = [], $return = false)
    {
        $data = array_merge($data, [
            'pageName' => 'styleguide'
        ]);
        $html  = Render::partial("styleguide/header", $data, true);
        $html .= Render::view($path, $data, true);
        $html .= Render::partial("styleguide/footer", $data, true);

        if ($return) {
            return $html;
        }

        echo $html;
    }

    function render($segments, $routePath)
    {
        $iter = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->dirPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
            RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
        );

        $dirListing = [];
        foreach ($iter as $path => $dir) {
            if ($dir->isFile()) {
                $list      = explode('/', $path);
                $file      = array_pop($list);
                $directory = array_pop($list);

                $dirListing[$directory]   = (empty($dirListing[$directory])) ? [] : $dirListing[$directory];
                $dirListing[$directory][] = $file;
            }
        }

        $navListing = [];
        foreach ($dirListing as $name => $files) {
            $navListing[$name] = array_map(function ($file) {
                $fileName = preg_replace('/\.[\w]+/', '', $file);

                return [
                    'file' => $fileName,
                    'name' => preg_replace('/[_-]/', ' ', $fileName)
                ];
            }, $files);
        }


        $pagePath = "styleguide$routePath";
        if (empty($segments)) {
            $blacklist = ['utils'];

            $html = '';
            foreach ($navListing as $base => $files) {
                foreach ($files as $file) {
                    if (in_array($file['file'], $blacklist)) {
                        continue;
                    }
                    $path = join('/', [
                        $pagePath,
                        $base,
                        $file['file']
                    ]);
                    $html .= '<h2>' . ucwords($file['name'], true) . '</h2>';
                    $html .= Render::view($path, [], true);
                }
            }
            $page = $html;

        } else if (count($segments) === 1) {
            $html  = '';
            $files = $navListing[first($segments)];
            foreach ($files as $fileInfo) {
                $path = join('/', [
                    $pagePath,
                    $fileInfo['file']
                ]);
                $html .= '<h2>' . ucwords($fileInfo['name'], true) . '</h2>';
                $html .= Render::view($path, [], true);
            }
            $page = $html;
        } else {
            $page = Render::view($pagePath, [], true);
        }

        $segmentsCopy = array_values($segments);
        $pageName     = array_pop($segmentsCopy);
        return static::view('styleguide', [
            'navListing' => $navListing,
            'page'       => $page,
            'name'       => $pageName
        ], true);
    }

}
