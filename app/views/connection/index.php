<?php
require_once dirname(__DIR__) . DS . 'templates' . DS . 'header.php';
require_once dirname(__DIR__, 2) . DS . 'controllers' . DS . 'connectionController.php';
$errors = $args['errors'] ?? '';
$valeursEchappees = $args['valeursEchappees'] ?? '';
?>
<div class="container">

    <!-- affichage des messages d'erreurs -->

    <?php if (!empty($args['errorMessage'])) : ?>
        <div class="error-message">
            <?= htmlspecialchars($args['errorMessage'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <!-- formulaire de connexion -->

    <form method="POST" action="">
        <fieldset>
            <legend>Connexion</legend>
            <p>
                <label for="pseudo">Pseudo&nbsp;:</label><br>
                <input type="text" name="pseudo" value="<?= htmlspecialchars($valeursEchappees['pseudo'] ?? "", ENT_QUOTES, 'UTF-8') ?>" /><br>
            </p>

            <?php if (!empty($errors['pseudo'])) : ?>
                <div class="error-value"> <?= htmlspecialchars($errors['pseudo'], ENT_QUOTES, 'UTF-8') ?> </div>
            <?php endif; ?>
            <p>
                <label for="motDePasse">Mot de passe&nbsp;:</label><br>
                <input type="password" id="motDePasse" name="motDePasse" value="<?= htmlspecialchars($valeursEchappees['motDePasse'] ?? "", ENT_QUOTES, 'UTF-8') ?>" /><br>
            </p>

            <?php if (!empty($errors['motDePasse'])) : ?>
                <div class="error-value"> <?= htmlspecialchars($errors['motDePasse'], ENT_QUOTES, 'UTF-8') ?> </div>
            <?php endif; ?>

            <p>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" id="submitBtn">Se connecter</button>
            </p>
            <p id="maCible"><a href="/inscription">S'inscrire</a></p>
        </fieldset>
    </form>
</div>
</body>
<?php require_once dirname(__DIR__) . DS . 'templates' . DS . 'footer.php'; ?>