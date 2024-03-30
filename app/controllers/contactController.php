<?php
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'formGestion.php';
require_once dirname(__DIR__) . DS . 'models' . DS . 'contactModel.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'messagesGestion.php';

$errors = [];
$valeursEchappees = [];

$champsConfig = obtenir_ChampsConfigsContact();

$formMessage = importer_messages('formMessages.json');

if (($_SERVER["REQUEST_METHOD"] === "POST")) {

    gestion_formulaire($formMessage, $champsConfig, $errors, $valeursEchappees);

    if (empty($errors)) {

        envoie_mail($valeursEchappees);

        $valeursEchappees = [];

        echo "<div style= 'text-align: center; font-size: 1.2em; color: green; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_succes"] . "</div>";
    } else {
        echo "<div style= 'text-align: center; font-size: 1.2em; color: red; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_echec"] . "</div>";
    }
}

function envoie_mail(array $valeursEchappees): void
{

    $destinataire = "th.lambot@hotmail.fr";
    $sujet = "Formulaire";
    $message = implode("\r\n", $valeursEchappees);

    if (mail($destinataire, $sujet, $message)) {
        echo "Le courriel a été envoyé avec succès.";
    } else {
        echo "L'envoi du courriel a échoué.";
    }
};
