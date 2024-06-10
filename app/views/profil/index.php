<?php
require_once dirname(__DIR__, 2) . DS . 'controllers' . DS . 'profilController.php';
require_once dirname(__DIR__) . DS . 'templates' . DS . 'header.php';
$utilisateur = $_SESSION['utilisateur'];
$errors = $args['errors'] ?? '';
$valeursEchappees = $args['valeursEchappees'] ?? '';
?>
<div class="container">

    <?php if (!empty($args['errors']['maj-failed-email'])) : ?>
        <div class="error-message">
            <?= htmlspecialchars($args['errors']['maj-failed-email'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])) : [] ?>
        <div class="success-message">
            <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <main>
        <!-- Formulaire de réinitialisation de l'email -->
        <div class="form-reset-email">
            <h2>Réinitialiser l'Email</h2>
            <form action="/profil" method="POST">
                <div class="form-group">
                    <label for="email-reset">Nouvel Email</label>
                    <input type="email" class="form-control" id="email-reset" name="email-reset" value="<?= htmlspecialchars($valeursEchappees['email-reset'] ?? "", ENT_QUOTES, 'UTF-8') ?>" placeholder="Entrez votre nouvel email">
                </div>

                <?php if (!empty($errors['email-reset'])) : ?>
                    <div class="error-value"><?= htmlspecialchars($errors['email-reset'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <!-- Inclusion du jeton CSRF -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="btn-primary">Mettre à jour l'email</button>
            </form>
        </div>

        <!-- Formulaire de réinitialisation du mot de passe -->
        <div class="form-reset-password">
            <h2>Réinitialiser le Mot de Passe</h2>
            <form action="/profil" method="POST">
                <div class="form-group">
                    <label for="password-reset">Nouveau Mot de Passe</label>
                    <input type="password" class="form-control" id="password-reset" name="password-reset" placeholder="Entrez votre nouveau mot de passe" required>
                </div>

                <?php if (!empty($errors['password-reset'])) : ?>
                    <div class="error-value"><?= htmlspecialchars($errors['password-reset'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="password-confirm">Confirme Mot de Passe</label>
                    <input type="password" class="form-control" id="password-confirm" name="password-confirm" placeholder="Confirmez votre nouveau mot de passe" required>
                </div>

                <?php if (!empty($errors['password-confirm'])) : ?>
                    <div class="error-value"><?= htmlspecialchars($errors['password-confirm'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <!-- Inclusion du jeton CSRF -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="btn-primary">Mettre à jour le mot de passe</button>
            </form>
        </div>

        <div class="content-profil">

            <h2>Informations du profil</h2>
            <p>Nom d'utilisateur: <?= htmlspecialchars($utilisateur['uti_pseudo'], ENT_QUOTES, 'UTF-8') ?></p>
            <p>Email: <?= htmlspecialchars($utilisateur['uti_email'], ENT_QUOTES, 'UTF-8') ?></p>
            <a href="/logout" class="logout-link">Déconnexion</a>
        </div>
    </main>
</div>
<?php require_once dirname(__DIR__) . DS . 'templates' . DS . 'footer.php'; ?>