<?php
// Inclusion des constantes de configuration
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'constantes.php';

// Initialisation de la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Génération du token CSRF s'il n'existe pas déjà
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Inclusion du contrôleur de confirmation
require_once __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . "confirmController.php";

// Définition des métadonnées pour la page
$metaDescription = "Page de confirmation";
$pageTitre = "Page de confirmation";

// Inclusion de l'en-tête
require_once __DIR__ . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'header.php';
?>
<div class="container">

    <?php if (!empty($errorMessage)) : ?>
        <div class="error-message">
            <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
    <main>
        <div class="content-mdp">
            <h1>Confirme ton email</h1>

            <!-- Formulaire de vérification de code -->
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="form_code" value="formVerificationIdentitie">
                <label for="verification_code">Code de vérification :</label>
                <input type="text" id="verification_code" name="verification_code" required>

                <?php if (!empty($errors['verification_code'])) : ?>
                    <div class="error-value">
                        <?= htmlspecialchars($errors['verification_code'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <input type="submit" value="Soumettre">
            </form>

            <!-- Formulaire pour demander un nouveau code -->
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="form_request" value="formEnvoyerCode">
                <input type="submit" value="Demander un nouveau code">
            </form>
        </div>
    </main>
</div>
<?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'footer.php'; ?>