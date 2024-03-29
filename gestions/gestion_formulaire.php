<?php
require_once 'includes/fonctions.php';

$errors = [];
$valeursEchappees = [];

$champsConfig = [
    'user_lastname' => [
        'requis' => true,
        'minLength' => 2,
        'maxLength' => 255
    ],
    'user_firstname' => [
        'requis' => false,
        'minLength' => 2,
        'maxLength' => 255
    ],
    'user_mail' => [
        'requis' => true,
        'type' => 'email'
    ],
    'user_message' => [
        'requis' => true,
        'minLength' => 10,
        'maxLength' => 3000
    ]
];

$formMessage = [
    "requis" => "Ce champs est requis",
    "email" => "Veuillez entrer une adresse mail valide!",
    "minMaxLength" => "Ce champs doit comprendre entre %0% et %1% caractères",
    "minLength" => "Ce champs doit avoir au moins %0% caractères",
    "maxLength" => "Ce champs doit avoir au plus %0% caractères",
    "envoi_echec" => "Le formulaire n'a pas été envoyé",
    "envoi_succes" => "Le formulaire a bien été envoyé"
];

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
