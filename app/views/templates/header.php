<?php require_once dirname(__DIR__, 3) . DS . 'core' . DS . 'menuGestion.php'; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $pageInfos['description'] ?? '' ?>">
    <title><?= $pageInfos['titre'] ?? "Mon site"; ?></title>
    <link rel="stylesheet" type="text/css" href="/public/css/styles.css">
    <script src="/public/javascript/app.js" type="module"></script>
</head>

<body>
    <nav>
        <ul>
            <li>Project <span>PHP</span></li>
        </ul>
        <ul>
            <?= nav_item('/', 'Accueil'); ?>
            <?= nav_item('/contact', 'Contact'); ?>
            <?= nav_item('/connexion', 'Connexion'); ?>
            <?= nav_item('/profil', 'Mon profil'); ?>
        </ul>
    </nav>