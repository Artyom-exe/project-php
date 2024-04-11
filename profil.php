<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'constantes.php';
require_once __DIR__ . DS . 'app' . DS . 'controllers' . DS . "profilController.php";


$metaDescription = "Profil utilisateur";
$pageTitre = "Profil utilisateur";
require_once __DIR__ . DS . 'components' . DS . 'header.php';
?>

<h1>Mon profil</h1>

<p><?= $utilisateur['uti_email'] ?></p>

<?php require_once __DIR__ . DS . 'components' . DS . 'footer.php'; ?>