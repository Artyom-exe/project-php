<?php
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'init.php';
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'confirmModel.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'formGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'authentificationGestion.php';

// Génération du token CSRF s'il n'existe pas
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function obtenir_pageInfos()
{
    return [
        'vue' => 'confirm',
        'titre' => 'Confirmation',
        'description' => '...'
    ];
}

function envoyer_code_activation($destinataire, $sujet, $formMessage)
{
    $_SESSION['code'] = rand(10000, 99999);
    if (mail($destinataire, $sujet, $_SESSION['code'])) {
        return $formMessage['mail_succes'] . " à l'adresse suivante : " . $destinataire;
    } else {
        return "Erreur lors de l'envoi de l'email.";
    }
}

function index($args = [])
{
    // Rediriger si les données de session sont manquantes
    if (!isset($_SESSION['verifierIdentite'])) {
        header("Location: /");
        exit();
    }

    $sujet = "Votre code d'activation";
    $destinataire = $_SESSION['verifierIdentite']['utiEmail'];
    $formMessage = importer_messages('formMessages.json');

    // Générer et envoyer le code d'activation s'il n'est pas déjà défini dans la session
    if (!isset($_SESSION['code']) || empty($_SESSION['code'])) {
        $args['successMessage'] = envoyer_code_activation($destinataire, $sujet, $formMessage);
    }

    // Mettre à jour le code d'activation dans la base de données
    try {
        $pdo = connexion_db();
        $requete = "UPDATE t_utilisateur_uti SET uti_code_activation = :codeActivation WHERE uti_email = :email";
        $stmt = $pdo->prepare($requete);
        $stmt->bindValue(':codeActivation', $_SESSION['code'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $destinataire, PDO::PARAM_STR);
        $stmt->execute();
    } catch (\PDOException $e) {
        gerer_exceptions($e);
    }

    // Inclure la vue avec les arguments
    afficher_vue(obtenir_pageInfos(), 'index', $args);
}

function insert()
{
    $args = [
        'errors' => [],
        'errorMessage' => '',
        'successMessage' => '',
        'valeursEchappees' => []
    ];

    $champsConfig = obtenir_ChampsConfigsModel();
    $formMessage = importer_messages('formMessages.json');
    $sujet = "Votre code d'activation";
    $destinataire = $_SESSION['verifierIdentite']['utiEmail'];

    // Gérer les requêtes POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Vérifier le token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $args['errors']['csrf_token'] = "Invalid CSRF token.";
        } else {
            // Renvoyer le code d'activation si demandé
            if (isset($_POST['form_request'])) {
                $args['successMessage'] = envoyer_code_activation($destinataire, $sujet, $formMessage);
            }

            // Vérifier le code d'activation s'il est soumis
            if (isset($_POST['form_code'])) {
                $args = gestion_formulaire($formMessage, $champsConfig['form_code']);

                if (empty($args['errors'])) {
                    if (isset($args['valeursEchappees']['verification_code']) && intval($args['valeursEchappees']['verification_code']) === $_SESSION['code']) {
                        try {
                            $pdo = connexion_db();
                            $requete = "UPDATE t_utilisateur_uti SET uti_compte_active = :compteActive WHERE uti_email = :email";
                            $stmt = $pdo->prepare($requete);
                            $stmt->bindValue(':compteActive', 1, PDO::PARAM_INT);
                            $stmt->bindValue(':email', $_SESSION['verifierIdentite']['utiEmail'], PDO::PARAM_STR);
                            $stmt->execute();
                        } catch (\PDOException $e) {
                            gerer_exceptions($e);
                        }

                        // Connecter l'utilisateur et rediriger
                        connecter_utilisateur($_SESSION['verifierIdentite']['utiId']);
                        $urlRedirection = $_SESSION['verifierIdentite']['urlRedirection'];
                        header("Location: $urlRedirection");
                        unset($_SESSION['verifierIdentite']);
                        unset($_SESSION['code']);
                        exit();
                    } else {
                        $args['errorMessage'] = $formMessage['code_incorrect'];
                    }
                }
            }
        }
    }
    index($args);
}
