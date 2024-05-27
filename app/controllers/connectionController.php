<?php

// Inclusion des fichiers nécessaires
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'formGestion.php';
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'authentificationModel.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'profilGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'authentificationGestion.php';

// Initialisation de la session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Génération du token CSRF s'il n'existe pas déjà
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Redirection si déjà connecté
if (isset($_SESSION['id']) && est_connecte($_SESSION['id'])) {
    header("location: /profil.php");
    exit();
}

$errors = []; // Tableau pour stocker les erreurs de formulaire
$valeursEchappees = []; // Tableau pour stocker les valeurs échappées des champs de formulaire

$champsConfig = obtenir_ChampsConfigsAuthentification(false); // Configuration des champs de formulaire

$formMessage = importer_messages('formMessages.json'); // Importation des messages du formulaire

// Vérification de la méthode de la requête
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors['csrf_token'] = "Token CSRF invalide.";
    }

    gestion_formulaire($formMessage, $champsConfig, $errors, $valeursEchappees); // Gestion du formulaire

    if (empty($errors)) {
        try {
            $pdo = connexion_db();

            $pseudo = $_POST['pseudo'];
            $motDePasse = $_POST['motDePasse'];

            $requete = "SELECT * FROM t_utilisateur_uti WHERE uti_pseudo = :pseudo";

            $stmt = $pdo->prepare($requete);

            $stmt->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);

            $stmt->execute();
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($utilisateur && password_verify($motDePasse, $utilisateur['uti_motdepasse'])) {

                // Informations pour vérification d'identité
                $verifierIdentite = [
                    "utiId" => $utilisateur['uti_id'],
                    "utiEmail" => $utilisateur['uti_email'],
                    "urlRedirection" => "/profil.php",
                    "envoyerCode" => true
                ];

                $_SESSION['verifierIdentite'] = $verifierIdentite;

                // Redirection selon le statut du compte
                if (empty($utilisateur['uti_code_activation'])) {
                    header("location: /confirm.php");
                    exit();
                } else {
                    connecter_utilisateur($_SESSION['verifierIdentite']['utiId']);
                    header("location: " . $_SESSION['verifierIdentite']['urlRedirection']);
                    exit();
                }
            } else {
                $errorMessage = $formMessage['id-mdp-echec'];
            }
        } catch (PDOException $e) {
            gerer_exceptions($e);
        }
    }
}
