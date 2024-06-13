<?php
require_once dirname(__DIR__) . DS . 'templates' . DS . 'header.php';
require_once dirname(__DIR__, 2) . DS . 'controllers' . DS . 'registerController.php';
$errors = $args['errors'] ?? '';
$valeursEchappees = $args['valeursEchappees'] ?? '';
?>
<div class="container">

    <?php if (!empty($args['successMessageRegister'])) : ?>
        <div class="success-message">
            <?= htmlspecialchars($args['successMessageRegister'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($args['register-errors'])) : ?>
        <div class="error-message">
            <?= htmlspecialchars($args['register-errors'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <fieldset>
            <legend>Inscription</legend>
            <p>
                <label for="pseudo">Pseudo&nbsp;:</label>
                <input type="text" name="pseudo" value="<?= htmlspecialchars($valeursEchappees['pseudo'] ?? "", ENT_QUOTES, 'UTF-8') ?>" />
                <?= !empty($errors['pseudo']) ? '<div class="error-value">' . htmlspecialchars($errors['pseudo'], ENT_QUOTES, 'UTF-8') . '</div>' : '' ?>
            </p>
            <p>
                <label for="motDePasse">Mot de passe&nbsp;:</label><br>
                <input type="password" id="motDePasse" name="motDePasse" value="<?= htmlspecialchars($valeursEchappees['motDePasse'] ?? "", ENT_QUOTES, 'UTF-8') ?>" /><br>
                <?= !empty($errors['motDePasse']) ? '<div class="error-value">' . htmlspecialchars($errors['motDePasse'], ENT_QUOTES, 'UTF-8') . '</div>' : '' ?>
            </p>
            <p>
                <label for="motDePasse_confirmation">Confirmer votre mot de passe&nbsp;:</label><br>
                <input type="password" id="motDePasse_confirmation" name="motDePasse_confirmation" value="<?= htmlspecialchars($valeursEchappees['motDePasse_confirmation'] ?? "", ENT_QUOTES, 'UTF-8') ?>" /><br>
                <?= !empty($errors['motDePasse_confirmation']) ? '<div class="error-value">' . htmlspecialchars($errors['motDePasse_confirmation'], ENT_QUOTES, 'UTF-8') . '</div>' : '' ?>
            </p>
            <p>
                <label for="email">E-mail&nbsp;:</label><br>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($valeursEchappees['email'] ?? "", ENT_QUOTES, 'UTF-8') ?>" /><br>
                <?= !empty($errors['email']) ? '<div class="error-value">' . htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') . '</div>' : '' ?>
            </p>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
            <p>
                <button type="submit">Je m'inscris</button>
            </p>
        </fieldset>
    </form>
</div>
<?php require_once dirname(__DIR__) . DS . 'templates' . DS . 'footer.php'; ?>