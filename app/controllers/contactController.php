<?php
// Initialisation de la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclusion des fichiers nécessaires
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'formGestion.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'contactModel.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'functions.php';

// Initialisation du token CSRF s'il n'existe pas déjà
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Initialisation des variables pour les erreurs et les valeurs échappées
$errors = [];
$valeursEchappees = [];

// Configuration des champs de formulaire et messages
$champsConfig = obtenir_ChampsConfigsContact();
$formMessage = importer_messages('formMessages.json');

// Vérification de la méthode de requête POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérification du token CSRF
    // Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors['csrf_token'] = "Token CSRF invalide.";
    } else {
        // Gestion du formulaire pour la validation et l'échappement des données
        gestion_formulaire($formMessage, $champsConfig, $errors, $valeursEchappees);

        // Si aucune erreur de validation
        if (empty($errors)) {
            // Envoi de l'e-mail avec les données échappées du formulaire
            envoie_mail($valeursEchappees);

            $succesMessageContact = $formMessage['envoi_succes'];
            // Réinitialisation des valeurs échappées pour éviter la réutilisation
            $valeursEchappees = [];
        }
    }
}
