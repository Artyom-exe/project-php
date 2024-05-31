<?php
function getConfig()
{
    $config = null;

    if ($config === null) {
        $config = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config.php';
    }

    return $config;
}
