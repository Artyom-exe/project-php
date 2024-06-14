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
            <!-- logo du site -->
            <li class="logo"><a href='/'>Project <span>PHP</span></a></li>
        </ul>
        <?php if (!isset($_SESSION['utilisateur'])) {
            echo "<ul>";
            echo nav_item('/', 'Home');
            echo nav_item('/contact', 'Contact');
            echo nav_item('/connexion', 'Connexion');
            echo nav_item('/inscription', 'S\'enregistrer');
            echo "</ul>";
        } else {
            echo "<ul>";
            echo nav_item('/', 'Home');
            echo nav_item('/contact', 'Contact');
            echo nav_item('/profil', 'Mon profil');
            echo nav_item('/logout', 'Déconnexion');
            echo "</ul>";
        }
        ?>
    </nav>