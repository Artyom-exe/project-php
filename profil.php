<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'constantes.php';
require_once __DIR__ . DS . 'app' . DS . 'controllers' . DS . 'profilController.php';

// Démarrer la session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Génération du token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$metaDescription = "Profil utilisateur";
$pageTitre = "Profil utilisateur";
require_once __DIR__ . DS . 'components' . DS . 'header.php';
?>
<div class="container">
    <main>
        <!-- Formulaire de réinitialisation -->
        <div class="form-reset">
            <h2>Réinitialiser le mot de passe et l'email</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="email">Nouvel Email</label>
                    <input type="email" class="form-control" id="email-reset" name="email-reset" placeholder="Entrez votre nouvel email">
                </div>
                <div class="form-group">
                    <label for="password">Nouveau Mot de Passe</label>
                    <input type="password" class="form-control" id="password-reset" name="password-reset" placeholder="Entrez votre nouveau mot de passe">
                </div>
                <div class="form-group">
                    <label for="password">Confirme Mot de Passe</label>
                    <input type="password" class="form-control" id="password-confirm" name="password-confirm" placeholder="Entrez votre nouveau mot de passe">
                </div>
                <!-- Inclusion du jeton CSRF -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="btn-primary">Mettre à jour</button>
            </form>
        </div>

        <div class="content-profil">
            <h2>Informations du profil</h2>
            <p>Nom d'utilisateur: <?= htmlspecialchars($utilisateur['uti_pseudo'], ENT_QUOTES, 'UTF-8') ?></p>
            <p>Email: <?= htmlspecialchars($utilisateur['uti_email'], ENT_QUOTES, 'UTF-8') ?></p>
            <a href="/app/controllers/logoutController.php" class="logout-link">Déconnexion</a>
        </div>
    </main>
</div>
<?php require_once __DIR__ . DS . 'components' . DS . 'footer.php'; ?>