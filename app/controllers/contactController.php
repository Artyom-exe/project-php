<?php
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'formGestion.php';
require_once dirname(__DIR__) . DS . 'models' . DS . 'contactModel.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'functions.php';

$errors = [];
$valeursEchappees = [];
$champsConfig = obtenir_ChampsConfigsContact();
$formMessage = importer_messages('formMessages.json');

if (($_SERVER["REQUEST_METHOD"] === "POST")) {

    gestion_formulaire($formMessage, $champsConfig, $errors, $valeursEchappees);

    if (empty($errors)) {

        envoie_mail($valeursEchappees);
        $valeursEchappees = [];
    }
}
