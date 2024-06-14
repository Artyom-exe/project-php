<?php
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'profilGestion.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'authentificationGestion.php';

GestionSession();

// Déconnecte l'utilisateur actuellement connecté et redirige vers la page de connexion.

function logout()
{
    deconnecter_utilisateur();
    header("location: /connexion");
}
