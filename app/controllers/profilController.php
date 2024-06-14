<?php
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'formGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'authentificationGestion.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'resetEmailModel.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'postModifyModel.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'resetMdpModel.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'postsModel.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'messagesGestion.php';

// Initialisation du token CSRF s'il n'existe pas déjà
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
        header("Location: /connexion");
        exit();
    }

    try {
        // Connexion à la base de données
        $pdo = connexion_db();

        // Préparation de la requête SQL pour récupérer les informations de l'utilisateur
        $requete = "SELECT uti_pseudo, uti_email, uti_id FROM t_utilisateur_uti WHERE uti_id = :id";
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

    // récupération des posts de l'utilisateur

    try {
        // Connexion à la base de données
        $pdo = connexion_db();

        // Préparation de la requête SQL pour récupérer les posts de l'utilisateurs 
        $requete =
            "SELECT uti_pseudo, pos_title, pos_content, pos_id
                    FROM t_utilisateur_uti
                    INNER JOIN p_posts_pos ON uti_id=pos_uti_id
                    WHERE uti_id = :id";

        $stmt = $pdo->prepare($requete);

        // Validation de l'ID de session
        $session_id = filter_var($_SESSION['id'], FILTER_VALIDATE_INT);
        if (
            $session_id === false || $session_id <= 0
        ) {
            throw new Exception("ID de session invalide.");
        }

        // Liaison de la valeur de l'ID de session à la requête préparée
        $stmt->bindValue(':id', $session_id, PDO::PARAM_INT);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des informations de l'utilisateur
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($posts) {
            // Stockage des posts dans le tableau d'arguments
            $args['posts-utilisateur'] = $posts;
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

// Fonction pour mettre à jour l'email de l'utilisateur
function updateEmail()
{
    $args = [];
    $champsConfig = obtenir_ChampsConfigsEmailReset();
    $formMessage = importer_messages('formMessages.json');

    // Validation du formulaire
    $args = gestion_formulaire($formMessage, $champsConfig);

    // Si le formulaire est valide, procéder à la redirection pour la confirmation par email
    if (empty($args['errors'])) {
        // Stocker l'email dans la session
        $_SESSION['new_email'] = $_POST['email-reset'];

        // Rediriger vers la page de confirmation
        header("Location: /confirm-email-mdp");
        exit();
    } else {
        $args['message-failed-profil'] = $formMessage['post-not-modify'];
    }

    // Retour à la page de profil en cas d'erreur
    index($args);
}

function updatePassword()
{
    $args = [];
    $champsConfig = obtenir_ChampsConfigsMdpReset(); // Configuration des champs de formulaire
    $formMessage = importer_messages('formMessages.json'); // Importation des messages du formulaire

    // Validation du formulaire
    $args = gestion_formulaire($formMessage, $champsConfig);

    // Si le formulaire est valide, procéder à la redirection pour la confirmation par email
    if (empty($args['errors'])) {
        // Stocker le nouveau mot de passe dans la session
        $_SESSION['new_password'] = $_POST['password-reset'];

        // Rediriger vers la page de confirmation
        header("Location: /confirm-email-mdp");
        exit();
    } else {
        $args['message-failed-profil'] = $formMessage['post-not-modify'];
    }

    // Retour à la page de profil en cas d'erreur
    index($args);
}

function sendPost()
{
    $args = [];
    $champsConfig = obtenir_ChampsConfigsPost();
    $formMessage = importer_messages('formMessages.json');

    // Validation du formulaire
    $args = gestion_formulaire($formMessage, $champsConfig);

    if (empty($args['errors'])) {
        // Connexion à la base de données
        $pdo = connexion_db();

        // Récupération des valeurs des champs
        $title = $args['valeursEchappees']['post-title'];
        $content = $args['valeursEchappees']['post-content'];
        $id = $_SESSION['utilisateur']['uti_id'];

        // Requête SQL pour insérer un nouveau post
        $requete = "INSERT INTO p_posts_pos (pos_title, pos_content, pos_uti_id) VALUES (:title, :content, :id)";

        try {
            // Préparation de la requête
            $stmt = $pdo->prepare($requete);

            // Liaison des valeurs aux paramètres de la requête
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':id', $id);

            // Exécution de la requête
            $stmt->execute();

            // Affichage du message de succès
            $args['message-success-profil'] = $formMessage['post-posted'];

            // Réinitialisation des valeurs échappées
            $args['valeursEchappees'] = [];
        } catch (PDOException $e) {
            // Affichage de l'erreur en cas d'échec de l'insertion
            $args['message-failed-profil'] = $formMessage['post-not-posted'];
        }
    } else {
        $args['message-failed-profil'] = $formMessage['post-not-modify'];
    }
    // Retour à la page de profil en cas d'erreur
    index($args);
}

function modifyPost($id)
{
    $args = [];
    $champsConfig = obtenir_ChampsConfigsModifyPost();
    $formMessage = importer_messages('formMessages.json');

    $args = gestion_formulaire($formMessage, $champsConfig);

    if (empty($args['errors'])) {

        // Connexion à la base de données
        $pdo = connexion_db();

        // Récupération des valeurs des champs
        $title = $_POST['post-title-modify'];
        $content = $_POST['post-content-modify'];

        // Requête SQL pour remplacer le contenu du post
        $requete = "UPDATE p_posts_pos SET pos_title = :title, pos_content = :content WHERE pos_id = :id";

        try {
            // Préparation de la requête
            $stmt = $pdo->prepare($requete);

            // Liaison des valeurs aux paramètres de la requête
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':id', $id);

            // Exécution de la requête
            $stmt->execute();

            // Affichage du message de succès
            $args['message-success-profil'] = $formMessage['post-modify'];

            // Réinitialisation des valeurs échappées
            $args['valeursEchappees'] = [];
        } catch (PDOException $e) {
            // Affichage de l'erreur en cas d'échec de l'insertion
            $args['message-failed-profil'] = $formMessage['post-not-modify'];
        }
    } else {
        $args['errors']['post_id'] = $id;
        $args['message-failed-profil'] = $formMessage['post-not-modify'];
    }


    // Retour à la page de profil en cas d'erreur
    index($args);
}

function deletePost($id)
{
    $args = [];
    $formMessage = importer_messages('formMessages.json'); // Importation des messages du formulaire
    // Connexion à la base de données
    $pdo = connexion_db();

    // Requête SQL pour supprimer le post
    $requete =
        "DELETE FROM p_posts_pos WHERE pos_id = :id";

    try {
        // Préparation de la requête
        $stmt = $pdo->prepare($requete);

        // Liaison des valeurs aux paramètres de la requête
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        // Exécution de la requête
        $stmt->execute();

        // Affichage du message de succès
        $args['message-success-profil'] = $formMessage['delete-post'];
    } catch (PDOException $e) {
        // Affichage de l'erreur en cas d'échec de l'insertion
        $args['message-failed-profil'] = $formMessage['failed-delete-post'];
    }
    index($args);
}


// Fonction pour traiter la soumission du formulaire
function insert()
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $args = [];

        // Vérification de la fréquence des requêtes
        $time_limit = 1; // 1 secondes
        if (isset($_SESSION['last_request_time'])) {
            $time_since_last_request = time() - $_SESSION['last_request_time'];
            if ($time_since_last_request < $time_limit) {
                $args['message-failed-profil'] = "Vous devez attendre $time_limit secondes entre chaque requête.";
                index($args);
                return;
            }
        }
        $_SESSION['last_request_time'] = time();

        // Vérification du token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $args['errors']['csrf_token'] = "Token CSRF invalide.";
        } else {
            if (isset($_POST['email-reset'])) {
                updateEmail();
            }
            if (isset($_POST['password-reset'])) {
                updatePassword();
            }
            if (isset($_POST['post-title'])) {
                sendPost();
            }
            if (isset($_POST['post_id_delete'])) {
                deletePost($_POST['post_id_delete']);
            }
            if (isset($_POST['post_id_modify'])) {
                modifyPost($_POST['post_id_modify']);
            }
        }
    } else {
        index();
    }
}
