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
            <input type="text" name="user_lastname" value="<?= (isset($valeursEchappees['user_lastname']) ? $valeursEchappees['user_lastname'] : "") ?> " /><br>
            <?= ((isset($errors['user_lastname']) ? $errors['user_lastname'] : "")) ?>
        </p>
        <p>
            <label for="firstname">Prénom&nbsp;:</label><br>
            <input type="text" id="firstname" name="user_firstname" value="<?= (isset($firstName) ? $firstName : "") ?>" /><br>
            <?= ((isset($errors['user_firstname']) ? $errors['user_firstname'] : "")) ?>
        </p>
        <p>
            <label for="mail">E-mail&nbsp;:</label><br>
            <input type="email" id="mail" name="user_mail" value="<?= (isset($mail) ? $mail : "") ?>" /><br>
            <?= ((isset($errors['user_mail']) ? $errors['user_mail'] : "")) ?>
        </p>
        <p>
            <label for="msg">Message&nbsp;:</label><br>
            <textarea id="msg" name="user_message"><?= (isset($message) ? $message : "") ?></textarea><br>
            <?= ((isset($errors['user_message']) ? $errors['user_message'] : "")) ?>
        </p>
        <p>
            <a href=""><button type="submit">Envoyer</button></a>
        </p>
    </fieldset>
</form>

<?php require_once 'footer.php'; ?>