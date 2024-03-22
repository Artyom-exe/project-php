<?php

$errors = [];
$valeursEchappees = [];

$champsConfig = [
    'inscription_pseudo' => [
        'requis' => true,
        'minLength' => 2,
        'maxLength' => 255
    ],
    'inscription_motDePasse' => [
        'requis' => true,
        'minLength' => 8,
        'maxLength' => 72
    ],
    'inscription_motDePasse_confirmation' => [
        'requis' => true,
        'minLength' => 8,
        'maxLength' => 72
    ],
    'inscription_email' => [
        'requis' => true,
        'type' => 'email'
    ]
];

$formMessage = [
    "requis" => "Ce champs est requis",
    "email" => "Veuillez entrer une adresse mail valide!",
    "minMaxLength" => "Ce champs doit comprendre entre %0% et %1% caractères",
    "minLength" => "Ce champs doit avoir au plus %0% caractères",
    "maxLength" => "Ce champs doit avoir au moins %0% caractères",
    "envoi_echec" => "Le formulaire n'a pas été envoyé",
    "envoi_succes" => "Le formulaire a bien été envoyé"
];

if (($_SERVER["REQUEST_METHOD"] === "POST")) {

    foreach ($champsConfig as $nomChamps => $regles) {

        $inscriptionPseudo = trim(htmlentities($_POST["inscription_pseudo"]));
        $valeursEchappees['inscription_pseudo'] = $inscriptionPseudo;

        if ((empty($inscriptionPseudo)) && ($champsConfig["inscription_pseudo"]["requis"])) {
            $errors['inscription_pseudo'] = $formMessage["requis"];
        } elseif (strlen($inscriptionPseudo) < $champsConfig["inscription_pseudo"]["minLength"]) {
            $errors['inscription_pseudo'] = $formMessage["maxLength"];
        } elseif (strlen($inscriptionPseudo) > $champsConfig["inscription_pseudo"]["maxLength"]) {
            $errors['inscription_pseudo'] = $formMessage["minLength"];
        }

        $inscriptionMotDePasse = trim(htmlentities($_POST["inscription_motDePasse"]));
        $valeursEchappees['inscription_motDePasse'] = $inscriptionMotDePasse;

        if ((empty($inscriptionMotDePasse)) && ($champsConfig["inscription_motDePasse"]["requis"])) {
            $errors['inscription_motDePasse'] = $formMessage["requis"];
        } elseif (strlen($inscriptionMotDePasse) < $champsConfig["inscription_motDePasse"]["minLength"]) {
            $errors['inscription_motDePasse'] = $formMessage["maxLength"];
        } elseif (strlen($inscriptionMotDePasse) > $champsConfig["inscription_motDePasse"]["maxLength"]) {
            $errors['inscription_motDePasse'] = $formMessage["minLength"];
        }

        $confInscriptionMdp = trim(htmlentities($_POST["inscription_motDePasse_confirmation"]));
        $valeursEchappees['inscription_motDePasse_confirmation'] = $confInscriptionMdp;

        if ((empty($confInscriptionMdp)) && ($champsConfig["inscription_motDePasse_confirmation"]["requis"])) {
            $errors['inscription_motDePasse_confirmation'] = $formMessage["requis"];
        } elseif (strlen($confInscriptionMdp) < $champsConfig["inscription_motDePasse_confirmation"]["minLength"]) {
            $errors['inscription_motDePasse_confirmation'] = $formMessage["maxLength"];
        } elseif (strlen($confInscriptionMdp) > $champsConfig["inscription_motDePasse_confirmation"]["maxLength"]) {
            $errors['inscription_motDePasse_confirmation'] = $formMessage["minLength"];
        }

        $inscription_email = trim(htmlentities($_POST["inscription_email"]));
        $valeursEchappees['inscription_email'] = $inscription_email;

        if (empty($inscription_email)) {
            $errors['inscription_email'] = $formMessage["requis"];
        } elseif (!filter_var($inscription_email, FILTER_VALIDATE_EMAIL)) {
            $errors['inscription_email'] = $formMessage["email"];
        }
    }

    if (empty($errors)) {
        $valeursEchappees = [];
        echo "<div style= 'text-align: center; font-size: 1.2em; color: green; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_succes"] . "</div>";
    } else {
        echo "<div style= 'text-align: center; font-size: 1.2em; color: red; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_echec"] . "</div>";
    }
};
