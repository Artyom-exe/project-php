<?php
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'profilGestion.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'authentificationGestion.php';

GestionSession();

function logout()
{
    deconnecter_utilisateur();
    header("location: /connexion");
}
