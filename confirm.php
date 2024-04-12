<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'constantes.php';
require_once __DIR__ . DS . 'app' . DS . 'controllers' . DS . "confirmController.php";

$metaDescription = "Page de confirmation";
$pageTitre = "Page de confirmation";
require_once __DIR__ . DS . 'components' . DS . 'header.php';
?>

<h1>Confime ton email</h1>

<form method="post">
    <input type="hidden" name="form_code" value="formVerificationIdentitie">
    <label for="verification_code">Code de vérification :</label>
    <input type="text" id="verification_code" name="verification_code">
    <?= $errors['verification_code'] ?? "" ?>
    <input type="submit" value="Soumettre">
</form>

<form method="post">
    <input type="hidden" name="form_request" value="formEnvoyerCode">
    <label for="email">Email :</label>
    <input type="email" id="email" name="email">
    <?= $errors['verification_code'] ?? "" ?>
    <input type="submit" value="Demander un nouveau code">
</form>



<?php require_once __DIR__ . DS . 'components' . DS . 'footer.php'; ?>