<?php
require_once dirname(__DIR__, 1) . DS . 'models' . DS . 'confirmModel.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'formGestion.php';

$errors = [];
$valeursEchappees = [];

$champsConfig = obtenir_ChampsConfigsModel();

$formMessage = importer_messages('formMessages.json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['form_code'])) {
        // Gérer le formulaire de vérification
        gestion_formulaire($formMessage, $champsConfig['form_code'], $errors, $valeursEchappees);
    } elseif (isset($_POST['form_request'])) {
        // Gérer le formulaire de demande de nouveau code
        gestion_formulaire($formMessage, $champsConfig['form_request'], $errors, $valeursEchappees);
    }

    if (empty($errors)) {
        echo "<div style= 'text-align: center; font-size: 1.2em; color: green; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_succes"] . "</div>";
    } else {
        echo "<div style= 'text-align: center; font-size: 1.2em; color: red; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_echec"] . "</div>";
    }
}
