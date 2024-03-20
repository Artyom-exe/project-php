<?php require 'menu.php';?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?=$metaDescription ?? ''?>">
    <title><?=(isset($pageTitre)) ?  $pageTitre : "Mon site";?></title>
    <link rel="stylesheet" type="text/css" href="assets/styles.css">
    <script src="app.js" type="module"></script>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li>Project <span>PHP</span></li>
            </ul>
            <ul>
                <?= nav_item('index.php', 'Accueil');?>
                <?= nav_item('contact.php', 'Contact');?>
            </ul>
        </nav>
    </header>
