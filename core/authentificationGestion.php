<?php
require_once __DIR__ . DS . "profilGestion.php";

// Constante pour la clé de session
define('SESSION_KEY_ID', 'id');

/**
 * Connecte un utilisateur en définissant sa session.
 *
 * @param int $id L'identifiant de l'utilisateur à connecter.
 */
function connecter_utilisateur(int $id): void
{
    GestionSession();
    $_SESSION[SESSION_KEY_ID] = $id;
}

/**
 * Déconnecte l'utilisateur en supprimant sa session.
 */
function deconnecter_utilisateur(): void
{
    session_destroy();
}

/**
 * Vérifie si un utilisateur est connecté.
 *
 * @return bool Vrai si un utilisateur est connecté, faux sinon.
 */
function est_connecte(): bool
{
    return isset($_SESSION[SESSION_KEY_ID]);
}
