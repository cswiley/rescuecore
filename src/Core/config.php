<?php

function config($key = '', $fallback = null) {

    static $config = null;

    if (!$config) {
        $config = parseYaml(DOCUMENT_ROOT . '/config.yml');
    }

    if (empty($key)) {
        return $config;
    }

    $dotConfig = array_dot($config);
    $res = array_get($dotConfig, $key);

    return (isset($res)) ? $res : $fallback;
}



