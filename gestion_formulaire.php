<?php

$errors = [];
$valeursEchappees = [];

$champsConfig = [
    'user_lastname' => [
        'requis' => true,
        'minLength' => 2,
        'maxLength' => 255
    ],
    'user_firstname' => [
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
    "minLength" => "Ce champs doit avoir au plus %0% caractères",
    "maxLength" => "Ce champs doit avoir au moins %0% caractères",
    "envoi_echec" => "Le formulaire n'a pas été envoyé",
    "envoi_succes" => "Le formulaire a bien été envoyé",
];


foreach ($champsConfig as $nomChamps => $regles) {


    if (($_SERVER["REQUEST_METHOD"] === "POST")) {

        $lastName = htmlentities($_POST["user_lastname"]);
        if (empty($lastName)) {
            $errors['user_lastname'] = $formMessage["requis"];
        } else {
            $valeursEchappees['user_lastname'] = "user_lastname";
        }
        // $firstName = htmlentities($_POST["user_firstname"]);
        // if (((strlen($firstName) < 2)) && (!empty($firstName))) {
        //     $errors['user_firstname'] =  "Le champ 'Prénom' est trop petit";
        // } elseif ((strlen($firstName) > 255)) {
        //     $errors['user_firstname'] =  "Le champ 'Prénom' est trop grand";
        // }
        // $Mail = htmlentities($_POST["user_mail"]);
        // if (empty($Mail) || !filter_var($Mail, FILTER_VALIDATE_EMAIL)) {
        //     $errors['user_mail'] =  "Le champ 'Mail' est invalide";
        // }
        // $message = htmlentities($_POST["user_message"]);
        // if (empty($message)) {
        //     $errors['user_message'] =  "Le champ 'Message' est vide";
        // } elseif ((strlen($firstName) < 10) && (!empty($firstName))) {
        //     $errors['user_message'] =  "Le champ 'Message' est trop petit";
        // } elseif (strlen($firstName) > 300) {
        //     $errors['user_message'] =  "Le champ 'Message' est trop grand";
        // }

        // if (empty($errors)) {
        //     echo "<div style= 'text-align: center; font-size: 1.2em; color: green; font-weight: bold; margin: 10px;'>Formulaire soumis avec succès !</div>";
        // } else {
        //     echo "<div style= 'text-align: center; font-size: 1.2em; color: red; font-weight: bold; margin: 10px;'>Le formulaire n'a pas été envoyé !</div>";
        // }
    }
};
