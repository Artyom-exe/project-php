<?php
require_once __DIR__ . DS . "profilGestion.php";

function connecter_utilisateur(int $id): void
{

    GestionSession();
    $_SESSION['id'] = $id;
}

function deconnecter_utilisateur()
{
    session_destroy();
}

function est_connecte(string $variable): bool
{
    if (isset($variable)) {
        return true;
    } else {
        return false;
    }
}
