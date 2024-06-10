<?php
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'init.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'formGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'authentificationGestion.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'resetMdpEmailModel.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'messagesGestion.php';

// Initialisation du token CSRF s'il n'existe pas déjà
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fonction pour obtenir les informations de la page
function obtenir_pageInfos()
{
    return [
        'vue' => 'profil',
        'titre' => 'Profil',
        'description' => 'Informations du profil utilisateur.'
    ];
}

// Fonction principale pour afficher le profil utilisateur
function index($args = [])
{
    // Vérification de la session et de la connexion de l'utilisateur
    if (!isset($_SESSION['id']) || !est_connecte($_SESSION['id'])) {
        header("Location: /connexion"); // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
        exit(); // Arrêt du script pour éviter toute exécution supplémentaire
    }

    try {
        // Connexion à la base de données
        $pdo = connexion_db();

        // Préparation de la requête SQL pour récupérer les informations de l'utilisateur
        $requete = "SELECT uti_pseudo, uti_email FROM t_utilisateur_uti WHERE uti_id = :id";
        $stmt = $pdo->prepare($requete);

        // Validation de l'ID de session
        $session_id = filter_var($_SESSION['id'], FILTER_VALIDATE_INT);
        if ($session_id === false || $session_id <= 0) {
            throw new Exception("ID de session invalide.");
        }

        // Liaison de la valeur de l'ID de session à la requête préparée
        $stmt->bindValue(':id', $session_id, PDO::PARAM_INT);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des informations de l'utilisateur
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur) {
            // Stockage des informations de l'utilisateur dans une variable de session
            $_SESSION['utilisateur'] = $utilisateur;
        } else {
            throw new Exception("Utilisateur non trouvé.");
        }
    } catch (PDOException $e) {
        // Gestion des exceptions PDO
        $args['error'] = "Erreur PDO : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    } catch (Exception $e) {
        // Gestion des autres exceptions
        $args['error'] = "Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }

    // Inclure la vue avec les arguments
    afficher_vue(obtenir_pageInfos(), 'index', $args);
}

// Fonction pour insérer ou mettre à jour les données de l'utilisateur
function insert()
{
    $args = [
        'errors' => [],
        'valeursEchappees' => []
    ];

    $champsConfig = obtenir_ChampsConfigsProfilReset(); // Configuration des champs de formulaire
    $formMessage = importer_messages('formMessages.json'); // Importation des messages du formulaire

    // Vérification de la méthode de la requête
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // Vérification du token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $args['errors']['csrf_token'] = "Token CSRF invalide.";
        } else {
            if (isset($_POST['email-reset'])) {
                // Validation du formulaire
                $args = gestion_formulaire($formMessage, $champsConfig);

                // Si le formulaire est valide, procéder à la mise à jour de l'email
                if (empty($args['errors'])) {

                    try {
                        // Connexion à la base de données
                        $pdo = connexion_db();

                        // Préparation de la requête SQL pour mettre à jour l'email de l'utilisateur
                        $stmt = $pdo->prepare("UPDATE t_utilisateur_uti SET uti_email = :email WHERE uti_id = :id");
                        $stmt->bindValue(':email', $_POST['email-reset'], PDO::PARAM_STR);
                        $stmt->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
                        $stmt->execute();

                        // Message de succès
                        $args['success'] = $formMessage['mail-change'];
                    } catch (PDOException $e) {
                        // Gestion des exceptions PDO
                        $args['error'] = $formMessage['maj-failed-email'] . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
                    }
                } else {
                    $args['errors']['email'] = $formMessage['email'];
                }
            }
        }
    }

    // Retour à la page de profil
    index($args);
}
