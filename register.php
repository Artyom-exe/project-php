<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'constantes.php';

// Démarrer la session pour gérer le token CSRF
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Génération du token CSRF s'il n'existe pas
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$metaDescription = "Inscription";
$pageTitre = "Inscription";
require_once __DIR__ . DS . 'components' . DS . 'header.php';
require_once __DIR__ . DS . 'app' . DS . 'controllers' . DS . 'registerController.php';
?>
<div class="container">
    <form method="POST" action="">
        <fieldset>
            <legend>Inscription</legend>
            <p>
                <label for="pseudo">Pseudo&nbsp;:</label><br>
                <input type="text" name="pseudo" value="<?= htmlspecialchars($valeursEchappees['pseudo'] ?? "", ENT_QUOTES, 'UTF-8') ?>" /><br>
                <?= htmlspecialchars($errors['pseudo'] ?? "", ENT_QUOTES, 'UTF-8') ?>
            </p>
            <p>
                <label for="motDePasse">Mot de passe&nbsp;:</label><br>
                <input type="password" id="motDePasse" name="motDePasse" value="<?= htmlspecialchars($valeursEchappees['motDePasse'] ?? "", ENT_QUOTES, 'UTF-8') ?>" /><br>
                <?= htmlspecialchars($errors['motDePasse'] ?? "", ENT_QUOTES, 'UTF-8') ?>
            </p>
            <p>
                <label for="motDePasse_confirmation">Confirmer votre mot de passe&nbsp;:</label><br>
                <input type="password" id="motDePasse_confirmation" name="motDePasse_confirmation" value="<?= htmlspecialchars($valeursEchappees['motDePasse_confirmation'] ?? "", ENT_QUOTES, 'UTF-8') ?>" /><br>
                <?= htmlspecialchars($errors['motDePasse_confirmation'] ?? "", ENT_QUOTES, 'UTF-8') ?>
            </p>
            <p>
                <label for="email">E-mail&nbsp;:</label><br>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($valeursEchappees['email'] ?? "", ENT_QUOTES, 'UTF-8') ?>" /><br>
                <?= htmlspecialchars($errors['email'] ?? "", ENT_QUOTES, 'UTF-8') ?>
            </p>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <p>
                <button type="submit">Je m'inscris</button>
            </p>
        </fieldset>
    </form>
</div>
<?php require_once __DIR__ . DS . 'components' . DS . 'footer.php'; ?>