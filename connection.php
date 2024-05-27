<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'constantes.php';

// Initialisation de la session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Génération du token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$metaDescription = "Inscription";
$pageTitre = "Inscription";

require_once __DIR__ . DS . 'components' . DS . 'header.php';
require_once __DIR__ . DS . 'app' . DS . 'controllers' . DS . 'connectionController.php';
?>
<div class="container">

    <?php if (!empty($errorMessage)) : ?>
        <div class="error-message">
            <?= htmlspecialchars($errorMessage ?? "", ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <fieldset>
            <legend>Connexion</legend>
            <p>
                <label for="pseudo">Pseudo&nbsp;:</label><br>
                <input type="text" name="pseudo" value="<?= htmlspecialchars($valeursEchappees['pseudo'] ?? "") ?>" /><br>
            </p>

            <?php if (!empty($errors['pseudo'])) : ?>
                <div class="error-value"> <?= $errors['pseudo'] ?> </div>
            <?php endif; ?>
            <p>
                <label for="motDePasse">Mot de passe&nbsp;:</label><br>
                <input type="password" id="motDePasse" name="motDePasse" value="<?= htmlspecialchars($valeursEchappees['motDePasse'] ?? "") ?>" /><br>
            </p>

            <?php if (!empty($errors['motDePasse'])) : ?>
                <div class="error-value"> <?= $errors['motDePasse'] ?> </div>
            <?php endif; ?>

            <p>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <button type="submit" id="submitBtn">Se connecter</button>
            </p>
            <p id="maCible"><a href="/register.php">S'inscrire</a></p>
        </fieldset>
    </form>
</div>
</body>
<?php require_once __DIR__ . DS . 'components' . DS . 'footer.php'; ?>