<?php
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'init.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'authentificationGestion.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'resetMdpEmailModel.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'messagesGestion.php';

function obtenir_pageInfos()
{
    return [
        'vue' => 'profil',
        'titre' => 'Profil',
        'description' => '...'
    ];
}

function index()
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

        // Stockage des informations de l'utilisateur dans une variable de session
        $_SESSION['utilisateur'] = $utilisateur;
    } catch (PDOException $e) {
        // Gestion des exceptions PDO
        echo "Erreur PDO : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    } catch (Exception $e) {
        // Gestion des autres exceptions
        echo "Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }

    // Inclure la vue avec les arguments
    afficher_vue(obtenir_pageInfos(), 'index');
}

index();
