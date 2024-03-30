<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'constantes.php';

$metaDescription = "Inscription";
$pageTitre = "Inscription";
require_once __DIR__ . DS . 'components' . DS . 'header.php';
require_once __DIR__ . DS . 'app' . DS . 'controllers' . DS . 'registerController.php';
?>

<form method="POST" action="">
    <fieldset>
        <legend>Inscription</legend>
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
            <label for="motDePasse_confirmation">Confirmer votre mot de passe&nbsp;:</label><br>
            <input type="password" id="motDePasse_confirmation" name="motDePasse_confirmation" value="<?= $valeursEchappees['motDePasse_confirmation'] ?? "" ?>"></input><br>
            <?= $errors['motDePasse_confirmation'] ?? "" ?>
        </p>
        <p>
            <label for="email">E-mail&nbsp;:</label><br>
            <input type="email" id="email" name="email" value="<?= $valeursEchappees['email'] ?? "" ?>" /><br>
            <?= $errors['email'] ?? "" ?>
        </p>
        <p>
            <a href=""><button type="submit">Je m'inscris</button></a>
        </p>
    </fieldset>
</form>

<?php require_once __DIR__ . DS . 'components' . DS . 'footer.php'; ?>