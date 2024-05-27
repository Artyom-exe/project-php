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

require_once __DIR__ . DS . 'components' . DS . 'header.php';
require_once __DIR__ . DS . 'app' . DS . 'controllers' . DS . 'contactController.php';

$metaDescription = "Contact";
$pageTitre = "Contact";
?>
<div class="container">

    <?php if (!empty($succesMessageContact)) : ?>
        <div class="success-message">
            <?= htmlspecialchars($succesMessageContact ?? "", ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <fieldset>
            <legend>Contact</legend>
            <p>
                <label for="lastname">Nom&nbsp;:</label><br>
                <input type="text" name="user_lastname" value="<?= htmlspecialchars($valeursEchappees['user_lastname'] ?? "", ENT_QUOTES, 'UTF-8') ?>" /><br>
            </p>
            <?php if (!empty($errors['user_lastname'])) : ?>
                <div class="error-value"> <?= $errors['user_lastname'] ?> </div>
            <?php endif; ?>
            <p>
                <label for="firstname">Prénom&nbsp;:</label><br>
                <input type="text" id="firstname" name="user_firstname" value="<?= htmlspecialchars($valeursEchappees['user_firstname'] ?? "", ENT_QUOTES, 'UTF-8') ?>" />
            </p>
            <?php if (!empty($errors['user_firstname'])) : ?>
                <div class="error-value"> <?= $errors['user_firstname'] ?> </div>
            <?php endif; ?>
            <p>
                <label for="mail">E-mail&nbsp;:</label><br>
                <input type="email" id="mail" name="user_mail" value="<?= htmlspecialchars($valeursEchappees['user_mail'] ?? "", ENT_QUOTES, 'UTF-8') ?>" />
            </p>
            <?php if (!empty($errors['user_mail'])) : ?>
                <div class="error-value"> <?= $errors['user_mail'] ?> </div>
            <?php endif; ?>
            <p>
                <label for="msg">Message&nbsp;:</label><br>
                <textarea id="msg" name="user_message"><?= htmlspecialchars($valeursEchappees['user_message'] ?? "", ENT_QUOTES, 'UTF-8') ?></textarea>
            </p>
            <?php if (!empty($errors['user_message'])) : ?>
                <div class="error-value"> <?= $errors['user_message'] ?> </div>
            <?php endif; ?>
            <p>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <button type="submit">Envoyer</button>
            </p>
        </fieldset>
    </form>
</div>
<?php require_once __DIR__ . DS . 'components' . DS . 'footer.php'; ?>