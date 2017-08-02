<?php

function env($key = '', $fallback = null)
{
    static $environment = null;

    if (!$environment) {
        $environment = parseYaml(DOCUMENT_ROOT . '/environment.yml');
    }

    if (empty($key)) {
        return $environment;
    }

    $dotEnvironment = array_dot(parseYaml(DOCUMENT_ROOT . '/environment.yml'));

    $res = array_get($dotEnvironment, $key);

    return (isset($res)) ? $res : $fallback;
}


