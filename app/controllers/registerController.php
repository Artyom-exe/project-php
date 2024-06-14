<?php
// Inclusion des fichiers nécessaires
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'formGestion.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'authentificationModel.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'authentificationGestion.php';

// Génération du token CSRF s'il n'existe pas
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function obtenir_pageInfos()
{
    return [
        'vue' => 'register',
        'titre' => 'Inscription',
        'description' => 'Page pour s\'enregistrer'
    ];
}

function index($args = [])
{
    // Redirection vers la page de profil si l'utilisateur est déjà connecté
    if (isset($_SESSION["id"]) && est_connecte($_SESSION["id"])) {
        header("Location: /profil");
        exit;
    }

    // Inclure la vue avec les arguments
    afficher_vue(obtenir_pageInfos(), 'index', $args);
}

function insert()
{
    // Initialisation du tableau d'arguments avec des valeurs par défaut

    $args = [
        'errors' => [],
        'successMessageRegister' => '',
        'valeursEchappees' => []
    ];

    // Vérification de la fréquence des requêtes
    $time_limit = 1; // 1 secondes
    if (isset($_SESSION['last_request_time'])) {
        $time_since_last_request = time() - $_SESSION['last_request_time'];
        if ($time_since_last_request < $time_limit) {
            $args['register-errors'] = "Vous devez attendre $time_limit secondes entre chaque requête.";
            index($args);
            return;
        }
    }
    $_SESSION['last_request_time'] = time();

    // Récupération de la configuration des champs de formulaire
    $champsConfig = obtenir_ChampsConfigsAuthentification();

    // Récupération des messages de formulaire
    $formMessage = importer_messages('formMessages.json');

    // Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $args['errors']['csrf_token'] = "Token CSRF invalide.";
    } else {
        // Validation du formulaire et récupération des erreurs et des valeurs échappées
        $args = gestion_formulaire($formMessage, $champsConfig);

        // Vérification s'il n'y a pas d'erreurs dans le formulaire
        if (empty($args['errors'])) {
            // Connexion à la base de données
            $pdo = connexion_db();

            // Récupération des valeurs des champs
            $pseudo = $args['valeursEchappees']['pseudo'];
            $motDePasse = password_hash($args['valeursEchappees']['motDePasse'], PASSWORD_DEFAULT); // Hashage du mot de passe
            $email = $args['valeursEchappees']['email'];

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
                $args['successMessageRegister'] = $formMessage['register_success'];

                // Réinitialisation des valeurs échappées
                $args['valeursEchappees'] = [];
            } catch (PDOException $e) {
                // Affichage de l'erreur en cas d'échec de l'insertion
                echo "<div style='text-align: center; font-size: 1.2em; color: red; font-weight: bold; margin: 10px;'>Erreur lors de l'insertion dans la base de données : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
            }
        }
    }

    index($args);
}
