<?php
// Inclusion des fichiers nécessaires
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'formGestion.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'authentificationModel.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'authentificationGestion.php';


// Redirection vers la page de profil si l'utilisateur est déjà connecté
if (isset($_SESSION["id"]) && est_connecte($_SESSION["id"])) {
    header("Location: /profil.php");
    exit;
}

$errors = []; // Tableau pour stocker les erreurs de formulaire
$valeursEchappees = []; // Tableau pour stocker les valeurs échappées des champs de formulaire

// Récupération de la configuration des champs de formulaire
$champsConfig = obtenir_ChampsConfigsAuthentification();

// Récupération des messages de formulaire
$formMessage = importer_messages('formMessages.json');

// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors['csrf_token'] = "Token CSRF invalide.";
    } else {
        // Validation du formulaire et récupération des erreurs et des valeurs échappées
        gestion_formulaire($formMessage, $champsConfig, $errors, $valeursEchappees);

        // Vérification s'il n'y a pas d'erreurs dans le formulaire
        if (empty($errors)) {
            // Connexion à la base de données
            $pdo = connexion_db();

            // Récupération des valeurs des champs
            $pseudo = $valeursEchappees['pseudo'];
            $motDePasse = password_hash($valeursEchappees['motDePasse'], PASSWORD_DEFAULT); // Hashage du mot de passe
            $email = $valeursEchappees['email'];

            // Requête SQL pour insérer un nouvel utilisateur dans la base de données
            $requete = "INSERT INTO t_utilisateur_uti (uti_pseudo, uti_motdepasse, uti_email) VALUES (:pseudo, :mot_de_passe, :email)";

            try {
                // Préparation de la requête
                $stmt = $pdo->prepare($requete);

                // Liaison des valeurs aux paramètres de la requête
                $stmt->bindParam(':pseudo', $pseudo);
                $stmt->bindParam(':mot_de_passe', $motDePasse);
                $stmt->bindParam(':email', $email);

                // Exécution de la requête
                $stmt->execute();

                // Affichage du message de succès
                $successMessageRegister = $formMessage['register_success'];

                // Réinitialisation des valeurs échappées
                $valeursEchappees = [];
            } catch (PDOException $e) {
                // Affichage de l'erreur en cas d'échec de l'insertion
                echo "<div style='text-align: center; font-size: 1.2em; color: red; font-weight: bold; margin: 10px;'>Erreur lors de l'insertion dans la base de données : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
            }
        }
    }
}
