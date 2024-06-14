<?php
require_once dirname(__DIR__) . DS . 'templates' . DS . 'header.php';
require_once dirname(__DIR__, 2) . DS . 'controllers' . DS . 'contactController.php';
$errors = $args['errors'] ?? '';
$valeursEchappees = $args['valeursEchappees'] ?? '';
?>
<div class="container">

    <!-- affichage des messages d'erreurs ou de succès -->

    <?php if (!empty($args['succesMessageContact'])) : ?>
        <div class="success-message">
            <?= htmlspecialchars($args['succesMessageContact'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <!-- formulaire de contact -->

    <form method="POST" action="">
        <fieldset>
            <legend>Contact</legend>
            <p>
                <label for="lastname">Nom&nbsp;:</label>
                <input type="text" name="user_lastname" value="<?= htmlspecialchars($valeursEchappees['user_lastname'] ?? "", ENT_QUOTES, 'UTF-8') ?>" />
            </p>
            <?php if (!empty($errors['user_lastname'])) : ?>
                <div class="error-value"><?= htmlspecialchars($errors['user_lastname'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <p>
                <label for="firstname">Prénom&nbsp;:</label><br>
                <input type="text" id="firstname" name="user_firstname" value="<?= htmlspecialchars($valeursEchappees['user_firstname'] ?? "", ENT_QUOTES, 'UTF-8') ?>" />
            </p>
            <?php if (!empty($errors['user_firstname'])) : ?>
                <div class="error-value"><?= htmlspecialchars($errors['user_firstname'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <p>
                <label for="mail">E-mail&nbsp;:</label>
                <input type="email" id="mail" name="user_mail" value="<?= htmlspecialchars($valeursEchappees['user_mail'] ?? "", ENT_QUOTES, 'UTF-8') ?>" />
            </p>
            <?php if (!empty($errors['user_mail'])) : ?>
                <div class="error-value"><?= htmlspecialchars($errors['user_mail'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <p>
                <label for="msg">Message&nbsp;:</label>
                <textarea id="msg" name="user_message"><?= htmlspecialchars($valeursEchappees['user_message'] ?? "", ENT_QUOTES, 'UTF-8') ?></textarea>
            </p>
            <?php if (!empty($errors['user_message'])) : ?>
                <div class="error-value"><?= htmlspecialchars($errors['user_message'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <p>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit">Envoyer</button>
            </p>
        </fieldset>
    </form>
</div>
<?php require_once dirname(__DIR__) . DS . 'templates' . DS . 'footer.php'; ?>