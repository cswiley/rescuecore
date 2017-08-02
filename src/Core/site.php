<?php

function site($key = '', $fallback = null)
{
    static $site = null;

    if (!$site) {
        if (file_exists(DATA_PATH) . '/site.yml') {
            $site = parseYaml(DATA_PATH . '/site.yml');
        }
        else {
            $site = [];
        }
    }

    if (empty($key)) {
        return $site;
    }

    $dotSite = array_dot($site);
    $res     = array_get($dotSite, $key);
    return (isset($res)) ? $res : $fallback;
}


