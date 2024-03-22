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
    "minLength" => "Ce champs doit avoir au plus %0% caractères",
    "maxLength" => "Ce champs doit avoir au moins %0% caractères",
    "envoi_echec" => "Le formulaire n'a pas été envoyé",
    "envoi_succes" => "Le formulaire a bien été envoyé"
];


foreach ($champsConfig as $nomChamps => $regles) {


    if (($_SERVER["REQUEST_METHOD"] === "POST")) {

        $lastName = trim(htmlentities($_POST["user_lastname"]));
        $valeursEchappees['user_lastname'] = $lastName;

        if ((empty($lastName)) && ($champsConfig["user_lastname"]["requis"])) {
            $errors['user_lastname'] = $formMessage["requis"];
        } elseif (strlen($lastName) < $champsConfig["user_lastname"]["minLength"]) {
            $errors['user_lastname'] = $formMessage["maxLength"];
        } elseif (strlen($lastName) > $champsConfig["user_lastname"]["maxLength"]) {
            $errors['user_lastname'] = $formMessage["minLength"];
        }

        $firstName = trim(htmlentities($_POST["user_firstname"]));
        $valeursEchappees['user_firstname'] = $firstName;

        if ((empty($firstName)) && ($champsConfig["user_firstname"]["requis"])) {
            $errors['user_firstname'] = $formMessage["requis"];
        } elseif (strlen($firstName) < $champsConfig["user_firstname"]["minLength"]) {
            $errors['user_firstname'] = $formMessage["maxLength"];
        } elseif (strlen($firstName) > $champsConfig["user_firstname"]["maxLength"]) {
            $errors['user_firstname'] = $formMessage["minLength"];
        }

        $mail = htmlentities($_POST["user_mail"]);
        $valeursEchappees['user_mail'] = $mail;

        if (empty($mail)) {
            $errors['user_mail'] = $formMessage["requis"];
        } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $errors['user_mail'] = $formMessage["email"];
        }

        $message = trim(htmlentities($_POST["user_message"]));
        $valeursEchappees['user_message'] = $message;

        if ((empty($message)) && ($champsConfig["user_message"]["requis"])) {
            $errors['user_message'] = $formMessage["requis"];
        } elseif (strlen($message) < $champsConfig["user_message"]["minLength"]) {
            $errors['user_message'] = $formMessage["maxLength"];
        } elseif (strlen($message) > $champsConfig["user_message"]["maxLength"]) {
            $errors['user_message'] = $formMessage["minLength"];
        }
    }
}

if (empty($errors)) {
    echo "<div style= 'text-align: center; font-size: 1.2em; color: green; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_succes"] . "</div>";
} else {
    echo "<div style= 'text-align: center; font-size: 1.2em; color: red; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_echec"] . "</div>";
};
