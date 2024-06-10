<?php
require_once dirname(__DIR__, 2) . DS . 'controllers' . DS . 'confirmEmailMdpController.php';
require_once dirname(__DIR__) . DS . 'templates' . DS . 'header.php';
$errors = $args['errors'] ?? '';
$valeursEchappees = $args['valeursEchappees'] ?? '';
?>
<div class="container">

    <?php if (!empty($args['errorMessage'])) : [] ?>
        <div class="error-message">
            <?= htmlspecialchars($args['errorMessage'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($args['successMessage'])) : [] ?>
        <div class="success-message">
            <?= htmlspecialchars($args['successMessage'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
    <main>
        <div class="content-mdp">
            <h1>Vérification à double facteur</h1>

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
<?php require_once dirname(__DIR__) . DS . 'templates' . DS . 'footer.php';
