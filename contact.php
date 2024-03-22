<?php
$metaDescription = "Contact";
$pageTitre = "Contact";
require_once 'header.php';
require_once 'gestion_formulaire.php';
?>

<form method="POST" action="">
    <fieldset>
        <legend>Contact</legend>
        <p>
            <label for="lastname">Nom&nbsp;:</label><br>
            <input type="text" name="user_lastname" value="<?= $valeursEchappees['user_lastname'] ?? "" ?> " /><br>
            <?= $errors['user_lastname'] ?? "" ?>
        </p>
        <p>
            <label for="firstname">Prénom&nbsp;:</label><br>
            <input type="text" id="firstname" name="user_firstname" value="<?= $valeursEchappees['user_firstname'] ?? "" ?>" /><br>
            <?= $errors['user_firstname'] ?? "" ?>
        </p>
        <p>
            <label for="mail">E-mail&nbsp;:</label><br>
            <input type="email" id="mail" name="user_mail" value="<?= $valeursEchappees['user_mail'] ?? "" ?>" /><br>
            <?= $errors['user_mail'] ?? "" ?>
        </p>
        <p>
            <label for="msg">Message&nbsp;:</label><br>
            <textarea id="msg" name="user_message"><?= $valeursEchappees['user_message'] ?? "" ?></textarea><br>
            <?= $errors['user_message'] ?? "" ?>
        </p>
        <p>
            <a href=""><button type="submit">Envoyer</button></a>
        </p>
    </fieldset>
</form>

<?php require_once 'footer.php'; ?>