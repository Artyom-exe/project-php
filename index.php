<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'constantes.php';

$metaDescription = "Accueil";
$pageTitre = "Accueil";
require_once __DIR__ . DS . 'components' . DS . 'header.php';

// Initialisation de la session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="container"></div>

<?php require_once __DIR__ . DS . 'components' . DS . 'footer.php'; ?>