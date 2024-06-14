<?php
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'authentificationGestion.php';

// Déconnecte l'utilisateur actuellement connecté et redirige vers la page de connexion.

function logout()
{
    deconnecter_utilisateur();
    header("location: /connexion");
}
