<?php

function GestionSession()
{
    // Vérifier si la session n'est pas déjà démarrée
    if (session_status() === PHP_SESSION_NONE) {
        // Configuration sécurisée des paramètres de session
        session_set_cookie_params([
            'path' => '/',
            'secure' => false, // Mettez à true si vous utilisez HTTPS
            'httponly' => true,
            'samesite' => 'Lax' // Préférez 'Lax' ou 'Strict' pour renforcer la sécurité
        ]);

        // Démarrer la gestion des variables de session
        session_start();
    }
}
