<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'constantes.php';
require_once __DIR__ . DS . 'app' . DS . 'controllers' . DS . 'profilController.php';

// Démarrer la session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$metaDescription = "Profil utilisateur";
$pageTitre = "Profil utilisateur";
require_once __DIR__ . DS . 'components' . DS . 'header.php';
?>
<div class="container">
    <main>
        <div class="content-profil">
            <h2>Informations du profil</h2>
            <p>Nom d'utilisateur: <?= htmlspecialchars($utilisateur['uti_pseudo'], ENT_QUOTES, 'UTF-8') ?></p>
            <p>Email: <?= htmlspecialchars($utilisateur['uti_email'], ENT_QUOTES, 'UTF-8') ?></p>
            <a href="/app/controllers/logoutController.php" class="logout-link">Déconnexion</a>

        </div>
    </main>
</div>
<?php require_once __DIR__ . DS . 'components' . DS . 'footer.php'; ?>