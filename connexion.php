<?php
$metaDescription = "Inscription";
$pageTitre = "Inscription";
require_once 'includes/header.php';
require_once 'gestions/gestion_connexion.php';
?>

<form method="POST" action="">
    <fieldset>
        <legend>Connexion</legend>
        <p>
            <label for="connexion_pseudo">Pseudo&nbsp;:</label><br>
            <input type="text" name="connexion_pseudo" value="<?= $valeursEchappees['connexion_pseudo'] ?? "" ?> " /><br>
            <?= $errors['connexion_pseudo'] ?? "" ?>
        </p>
        <p>
            <label for="connexion_motDePasse">Mot de passe&nbsp;:</label><br>
            <input type="password" id="connexion_motDePasse" name="connexion_motDePasse" value="<?= $valeursEchappees['connexion_motDePasse'] ?? "" ?>" /><br>
            <?= $errors['connexion_motDePasse'] ?? "" ?>
        </p>
        <p>
            <a href=""><button type="submit">Se connecter</button></a>
        </p>
        <p><a href="/inscription.php">S'inscrire</a>
    </fieldset>
</form>

<?php require_once 'includes/footer.php'; ?>