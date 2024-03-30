<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'constantes.php';

$metaDescription = "Inscription";
$pageTitre = "Inscription";
require_once __DIR__ . DS . 'components' . DS . 'header.php';
require_once __DIR__ . DS . 'app' . DS . 'controllers' . DS . 'connectionController.php';
?>

<form method="POST" action="">
    <fieldset>
        <legend>Connexion</legend>
        <p>
            <label for="pseudo">Pseudo&nbsp;:</label><br>
            <input type="text" name="pseudo" value="<?= $valeursEchappees['pseudo'] ?? "" ?> " /><br>
            <?= $errors['pseudo'] ?? "" ?>
        </p>
        <p>
            <label for="motDePasse">Mot de passe&nbsp;:</label><br>
            <input type="password" id="motDePasse" name="motDePasse" value="<?= $valeursEchappees['motDePasse'] ?? "" ?>" /><br>
            <?= $errors['motDePasse'] ?? "" ?>
        </p>
        <p>
            <a href=""><button type="submit">Se connecter</button></a>
        </p>
        <p id="maCible"><a href="/register.php">S'inscrire</a>
    </fieldset>
</form>

<?php require_once __DIR__ . DS . 'components' . DS . 'footer.php'; ?>