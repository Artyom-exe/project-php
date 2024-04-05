<?php

function GestionSession()
{

    if (session_status() === PHP_SESSION_NONE) {

        ini_set('session.use_strict_mode', 1);

        // Empêcher la récupération du cookie de session via l'URL (1 est sa valeur par défaut, mais on est jamais trop prudent).
        ini_set('session.use_only_cookies', 1);

        // Configuration sécurisée de la variable de session avant de démarrer celle-ci.
        session_set_cookie_params([
            'path' => '/',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'lax'
        ]);

        // Démarrer la gestion des variables de session.
        session_start();
    }
}
