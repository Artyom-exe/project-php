<?php
// Inclusion des fichiers nécessaires
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'init.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'formGestion.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'contactModel.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'functions.php';

// Initialisation du token CSRF s'il n'existe pas déjà
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function obtenir_pageInfos()
{
    return [
        'vue' => 'contact',
        'titre' => 'Contact',
        'description' => '...'
    ];
}

function index($args = [])
{
    // Inclure la vue avec les arguments
    afficher_vue(obtenir_pageInfos(), 'index', $args);
}

function insert()
{
    $args = [
        'errors' => [],
        'successMessageContact' => '', // Ajoutez une clé pour le message de succès
        'valeursEchappees' => []
    ];
    // Configuration des champs de formulaire et messages
    $champsConfig = obtenir_ChampsConfigsContact();
    $formMessage = importer_messages('formMessages.json');

    // Vérification de la méthode de requête POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Vérification du token CSRF
        // Vérification du token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $ars['errors']['csrf_token'] = "Token CSRF invalide.";
        } else {
            // Gestion du formulaire pour la validation et l'échappement des données
            $args = gestion_formulaire($formMessage, $champsConfig);

            // Si aucune erreur de validation
            if (empty($args['errors'])) {
                // Envoi de l'e-mail avec les données échappées du formulaire
                envoie_mail($args['valeursEchappees']);

                $args['succesMessageContact'] = $formMessage['envoi_succes'];
                // Réinitialisation des valeurs échappées pour éviter la réutilisation
                $args['valeursEchappees'] = [];
            }
        }
    }
    index($args);
}
