<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'confirmModel.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'formGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'authentificationGestion.php';

$errors = [];
$valeursEchappees = [];

// Obtenir la configuration des champs et les messages
$champsConfig = obtenir_ChampsConfigsModel();
$formMessage = importer_messages('formMessages.json');

// Définir le sujet de l'email et le destinataire
$sujet = "Votre code d'activation";
$destinataire = $_SESSION['verifierIdentite']['utiEmail'];

// Rediriger si les données de session sont manquantes
if (!isset($_SESSION['verifierIdentite'])) {
    header("Location: /index.php");
    exit();
}

// Générer et envoyer le code d'activation s'il n'est pas déjà défini dans la session
if (!isset($_SESSION['code'])) {
    $_SESSION['code'] = rand(10000, 99999);
    mail($destinataire, $sujet, $_SESSION['code']);
    $successMessage = "Le courriel a été envoyé avec succès.";
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

// Gérer les requêtes POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérifier le token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errorMessage = "Invalid CSRF token.";
    } else {
        // Renvoyer le code d'activation si demandé
        if (isset($_POST['form_request'])) {
            mail($destinataire, $sujet, $_SESSION['code']);
        }

        // Vérifier le code d'activation s'il est soumis
        if (isset($_POST['form_code'])) {
            gestion_formulaire($formMessage, $champsConfig['form_code'], $errors, $valeursEchappees);

            if (empty($errors)) {
                if (isset($valeursEchappees['verification_code']) && intval($valeursEchappees['verification_code']) === $_SESSION['code']) {
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
                    exit();
                } else {
                    $errorMessage = $formMessage['code_incorrect'];
                }
            }
        }
    }
}
