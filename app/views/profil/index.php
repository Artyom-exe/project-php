<?php
require_once dirname(__DIR__, 2) . DS . 'controllers' . DS . 'profilController.php';
require_once dirname(__DIR__) . DS . 'templates' . DS . 'header.php';

$utilisateur = $_SESSION['utilisateur'];
$errors = $args['errors'] ?? '';
$valeursEchappees = $args['valeursEchappees'] ?? '';
?>
<div class="container">

    <!-- affichage des messages d'erreurs ou de succès -->

    <?php if (!empty($args['message-failed-profil'])) : ?>
        <div class="error-message">
            <?= htmlspecialchars($args['message-failed-profil'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($args['message-success-profil'])) : ?>
        <div class="success-message">
            <?= htmlspecialchars($args['message-success-profil'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if ((!empty($_SESSION['success']))) : ?>
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

        <!-- Formulaire d'ajout de post -->
        <div class="form-add-post">
            <h2>Ajouter un Nouveau Post</h2>
            <form action="/profil" method="POST">
                <div class="form-group">
                    <label for="post-title">Titre du Post</label>
                    <input type="text" class="form-control" id="post-title" name="post-title" value="<?= htmlspecialchars($valeursEchappees['post-title'] ?? "", ENT_QUOTES, 'UTF-8') ?>" placeholder="Entrez le titre de votre post">
                </div>

                <?php if (!empty($errors['post-title'])) : ?>
                    <div class="error-value"><?= htmlspecialchars($errors['post-title'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="post-content">Contenu du Post</label>
                    <textarea class="form-control" id="post-content" name="post-content" placeholder="Entrez le contenu de votre post"><?= htmlspecialchars($valeursEchappees['post-content'] ?? "", ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <?php if (!empty($errors['post-content'])) : ?>
                    <div class="error-value"><?= htmlspecialchars($errors['post-content'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <!-- Inclusion du jeton CSRF -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="btn-primary">Ajouter le Post</button>
            </form>
        </div>

        <div class="content-profil">

            <h2>Informations du profil</h2>
            <p>Nom d'utilisateur: <?= htmlspecialchars($utilisateur['uti_pseudo'], ENT_QUOTES, 'UTF-8') ?></p>
            <p>Email: <?= htmlspecialchars($utilisateur['uti_email'], ENT_QUOTES, 'UTF-8') ?></p>
            <a href="/logout" class="logout-link">Déconnexion</a>
        </div>

        <!-- Posts de l'utilisateur -->

        <?php

        if (isset($args['posts-utilisateur'])) {
            $posts = $args['posts-utilisateur'];
            $posts = array_reverse($posts); // Inverser l'ordre des posts
            for ($i = 0; $i < count($posts); $i++) {

                // Décoder les entités HTML pour chaque champ de contenu
                $posts[$i]['pos_title'] = html_entity_decode($posts[$i]['pos_title']);
                $posts[$i]['pos_content'] = html_entity_decode($posts[$i]['pos_content']);

                echo "<div class='form-add-post'>";
                echo "<form action='/profil' method='POST'>"; // Formulaires principaux ouverts ici
                echo "<h3>" . htmlspecialchars($posts[$i]['uti_pseudo'], ENT_QUOTES, 'UTF-8') . "</h3><br>";

                echo "<div class='form-group'>";
                echo "<label for='post-title-modify'>Titre du Post</label>";
                echo "<input type='text' class='form-control' id='post-title-modify' name='post-title-modify' value='" . htmlspecialchars($posts[$i]['pos_title'], ENT_QUOTES, 'UTF-8') . "'>";
                echo "</div>";

                // Afficher le message d'erreur uniquement pour le post en cours de modification
                if (!empty($errors['post-title-modify']) && intval($posts[$i]['pos_id']) === intval($errors['post_id'])) {
                    echo "<div class='error-value'>" . htmlspecialchars($errors['post-title-modify'], ENT_QUOTES, 'UTF-8') . "</div>";
                }

                echo "<div class='form-group'>";
                echo "<label for='post-content-modif'>Contenu du Post</label>";
                echo "<textarea class='form-control' id='post-content-modify' name='post-content-modify'>" . htmlspecialchars($posts[$i]['pos_content'], ENT_QUOTES, 'UTF-8') . "</textarea>";
                echo "</div>";

                // Afficher le message d'erreur uniquement pour le post en cours de modification
                if (!empty($errors['post-content-modify']) && intval($posts[$i]['pos_id']) === intval($errors['post_id'])) {
                    echo "<div class='error-value'>" . htmlspecialchars($errors['post-content-modify'], ENT_QUOTES, 'UTF-8') . "</div>";
                }

                // Form for Edit action
                echo '<input type="hidden" name="post_id_modify" value="' . htmlspecialchars($posts[$i]['pos_id'], ENT_QUOTES, 'UTF-8') . '">';
                echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . '">';
                echo '<button type="submit" class="btn-primary edit-btn">Modifier</button>';

                // Form for Delete action
                echo '</form>'; // Fermeture du premier formulaire
                echo '<form action="" method="POST">'; // Deuxième formulaire ouvert
                echo '<input type="hidden" name="post_id_delete" value="' . htmlspecialchars($posts[$i]['pos_id'], ENT_QUOTES, 'UTF-8') . '">';
                echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . '">';
                echo '<button type="submit" class="btn-primary delete-btn">Supprimer</button>';
                echo '</form>'; // Fermeture du deuxième formulaire
                echo "</div>";
            }
        }
        ?>
    </main>
</div>
<?php require_once dirname(__DIR__) . DS . 'templates' . DS . 'footer.php'; ?>