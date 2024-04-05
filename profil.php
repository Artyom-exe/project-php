<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'constantes.php';

$metaDescription = "Profil utilisateur";
$pageTitre = "Profil utilisateur";
require_once __DIR__ . DS . 'components' . DS . 'header.php';
?>

<h1>Mon profil</h1>
<?= print_r($_SESSION) ?>

<?php require_once __DIR__ . DS . 'components' . DS . 'footer.php'; ?>