<?php require_once dirname(__DIR__) . DS . 'config' . DS . 'constantes.php'; ?>
<?php require_once dirname(__DIR__) . DS . 'core' . DS . 'menuGestion.php'; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $metaDescription ?? '' ?>">
    <title><?= (isset($pageTitre)) ?  $pageTitre : "Mon site"; ?></title>
    <link rel="stylesheet" type="text/css" href="/assets/css/styles.css">
    <script src="/assets/javascript/app.js" type="module"></script>
</head>

<body>
    <nav>
        <ul>
            <li>Project <span>PHP</span></li>
        </ul>
        <ul>
            <?= nav_item('index.php', 'Accueil'); ?>
            <?= nav_item('contact.php', 'Contact'); ?>
            <?= nav_item('connection.php', 'Connexion'); ?>
            <?= nav_item('profil.php', 'Mon profil'); ?>
            <?= nav_item('confirm.php', 'Confirmer'); ?>
        </ul>
    </nav>