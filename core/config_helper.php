<?php
// Charge et retourne la configuration une seule fois à partir du fichier config.php situé dans le répertoire parent.

function getConfig()
{
    $config = null;

    if ($config === null) {
        $config = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config.php';
    }

    return $config;
}
