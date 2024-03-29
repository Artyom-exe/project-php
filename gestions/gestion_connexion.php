<?php
require_once 'includes/fonctions.php';

$errors = [];
$valeursEchappees = [];

$champsConfig = [
    'connexion_pseudo' => [
        'requis' => true,
        'minLength' => 2,
        'maxLength' => 255
    ],
    'connexion_motDePasse' => [
        'requis' => true,
        'minLength' => 8,
        'maxLength' => 72
    ],
];

$formMessage = [
    "requis" => "Ce champs est requis",
    "email" => "Veuillez entrer une adresse mail valide!",
    "minMaxLength" => "Ce champs doit comprendre entre %0% et %1% caractères",
    "minLength" => "Ce champs doit avoir au moins %0% caractères",
    "maxLength" => "Ce champs doit avoir au plus %0% caractères",
    "envoi_echec" => "Un problème est survenu",
    "envoi_succes" => "Vous êtes connecté"
];

if (($_SERVER["REQUEST_METHOD"] === "POST")) {

    gestion_formulaire($formMessage, $champsConfig, $errors, $valeursEchappees);

    if (empty($errors)) {
        $valeursEchappees = [];
        echo "<div style= 'text-align: center; font-size: 1.2em; color: green; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_succes"] . "</div>";
    } else {
        echo "<div style= 'text-align: center; font-size: 1.2em; color: red; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_echec"] . "</div>";
    }
};
