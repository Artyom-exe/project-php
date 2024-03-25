<?php
$metaDescription = "Inscription";
$pageTitre = "Inscription";
require_once 'header.php';
require_once 'gestion_connexion.php';
?>

<form method="POST" action="">
    <fieldset>
        <legend>Connexion</legend>
        <p>
            <label for="inscription_pseudo">Pseudo&nbsp;:</label><br>
            <input type="text" name="inscription_pseudo" value="<?= $valeursEchappees['inscription_pseudo'] ?? "" ?> " /><br>
            <?= $errors['inscription_pseudo'] ?? "" ?>
        </p>
        <p>
            <label for="connexion_pseudo">Mot de passe&nbsp;:</label><br>
            <input type="password" id="connexion_pseudo" name="connexion_pseudo" value="<?= $valeursEchappees['connexion_pseudo'] ?? "" ?>" /><br>
            <?= $errors['connexion_pseudo'] ?? "" ?>
        </p>
        <p>
            <a href=""><button type="submit">Se connecter</button></a>
        </p>
        <p><a href="/inscription.php">S'inscrire</a>
    </fieldset>
</form>

<?php require_once 'footer.php'; ?>