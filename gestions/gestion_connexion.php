<?php

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
    "minLength" => "Ce champs doit avoir au plus %0% caractères",
    "maxLength" => "Ce champs doit avoir au moins %0% caractères",
    "envoi_echec" => "Un problème est survenu",
    "envoi_succes" => "Vous êtes connecté"
];

if (($_SERVER["REQUEST_METHOD"] === "POST")) {

    foreach ($champsConfig as $nomChamps => $regles) {

        $connexionPseudo = trim(htmlentities($_POST["connexion_pseudo"]));
        $valeursEchappees['connexion_pseudo'] = $connexionPseudo;

        if ((empty($connexionPseudo)) && ($champsConfig["connexion_pseudo"]["requis"])) {
            $errors['connexion_pseudo'] = $formMessage["requis"];
        } elseif (strlen($connexionPseudo) < $champsConfig["connexion_pseudo"]["minLength"]) {
            $errors['connexion_pseudo'] = $formMessage["maxLength"];
        } elseif (strlen($connexionPseudo) > $champsConfig["connexion_pseudo"]["maxLength"]) {
            $errors['connexion_pseudo'] = $formMessage["minLength"];
        }

        $connexionMotDePasse = trim(htmlentities($_POST["connexion_motDePasse"]));
        $valeursEchappees['connexion_motDePasse'] = $connexionMotDePasse;

        if ((empty($connexionMotDePasse)) && ($champsConfig["connexion_motDePasse"]["requis"])) {
            $errors['connexion_motDePasse'] = $formMessage["requis"];
        } elseif (strlen($connexionMotDePasse) < $champsConfig["connexion_motDePasse"]["minLength"]) {
            $errors['connexion_motDePasse'] = $formMessage["maxLength"];
        } elseif (strlen($connexionMotDePasse) > $champsConfig["connexion_motDePasse"]["maxLength"]) {
            $errors['connexion_motDePasse'] = $formMessage["minLength"];
        }
    }

    if (empty($errors)) {
        $valeursEchappees = [];
        echo "<div style= 'text-align: center; font-size: 1.2em; color: green; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_succes"] . "</div>";
    } else {
        echo "<div style= 'text-align: center; font-size: 1.2em; color: red; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_echec"] . "</div>";
    }
};
