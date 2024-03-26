<?php
$metaDescription = "Inscription";
$pageTitre = "Inscription";
require_once 'includes/header.php';
require_once 'gestions/gestion_inscription.php';
?>

<form method="POST" action="">
    <fieldset>
        <legend>Inscription</legend>
        <p>
            <label for="inscription_pseudo">Pseudo&nbsp;:</label><br>
            <input type="text" name="inscription_pseudo" value="<?= $valeursEchappees['inscription_pseudo'] ?? "" ?> " /><br>
            <?= $errors['inscription_pseudo'] ?? "" ?>
        </p>
        <p>
            <label for="inscription_motDePasse">Mot de passe&nbsp;:</label><br>
            <input type="password" id="inscription_motDePasse" name="inscription_motDePasse" value="<?= $valeursEchappees['inscription_motDePasse'] ?? "" ?>" /><br>
            <?= $errors['inscription_motDePasse'] ?? "" ?>
        </p>
        <p>
            <label for="inscription_motDePasse_confirmation">Confirmer votre mot de passe&nbsp;:</label><br>
            <input type="password" id="inscription_motDePasse_confirmation" name="inscription_motDePasse_confirmation"><?= $valeursEchappees['inscription_motDePasse_confirmation'] ?? "" ?></input><br>
            <?= $errors['inscription_motDePasse_confirmation'] ?? "" ?>
        </p>
        <p>
            <label for="inscription_email">E-mail&nbsp;:</label><br>
            <input type="email" id="inscription_email" name="inscription_email" value="<?= $valeursEchappees['inscription_email'] ?? "" ?>" /><br>
            <?= $errors['inscription_email'] ?? "" ?>
        </p>
        <p>
            <a href=""><button type="submit">Je m'inscris</button></a>
        </p>
    </fieldset>
</form>

<?php require_once 'includes/footer.php'; ?>