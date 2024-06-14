<?php
// Inclusion des fichiers nécessaires
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'formGestion.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'authentificationModel.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'profilGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'authentificationGestion.php';

// Génération du token CSRF s'il n'existe pas
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function obtenir_pageInfos()
{
    return [
        'vue' => 'connection',
        'titre' => 'Connexion',
        'description' => 'Page de connexion'
    ];
}

function index($args = [])
{
    // Redirection si déjà connecté
    if (isset($_SESSION['id']) && est_connecte($_SESSION['id'])) {
        header("location: /profil");
        exit();
    }

    // Inclure la vue avec les arguments
    afficher_vue(obtenir_pageInfos(), 'index', $args);
}

function insert()
{
    // Initialisation du tableau d'arguments avec des valeurs par défaut

    $args = [
        'errors' => [],
        'errorMessage' => '',
        'valeursEchappees' => []
    ];

    $champsConfig = obtenir_ChampsConfigsAuthentification(false);
    $formMessage = importer_messages('formMessages.json');

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // Vérification de la fréquence des requêtes
        $time_limit = 1; // 1 secondes
        if (isset($_SESSION['last_request_time'])) {
            $time_since_last_request = time() - $_SESSION['last_request_time'];
            if ($time_since_last_request < $time_limit) {
                $args['errorMessage'] = "Vous devez attendre $time_limit secondes entre chaque requête.";
                index($args);
                return;
            }
        }
        $_SESSION['last_request_time'] = time();

        // Vérifier le token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $args['errors']['csrf_token'] = "Token CSRF invalide.";
        } else {
            $args = gestion_formulaire($formMessage, $champsConfig);

            // Authentifie l'utilisateur en vérifiant le pseudo et le mot de passe, initialise la session en conséquence, et redirige selon l'état d'activation du compte.

            if (empty($args['errors'])) {
                try {
                    $pdo = connexion_db();

                    $pseudo = $_POST['pseudo'];
                    $motDePasse = $_POST['motDePasse'];

                    // Prépare et exécute la requête pour récupérer l'utilisateur par son pseudo

                    $requete = "SELECT * FROM t_utilisateur_uti WHERE uti_pseudo = :pseudo";
                    $stmt = $pdo->prepare($requete);
                    $stmt->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
                    $stmt->execute();

                    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Prépare et exécute la requête pour récupérer l'utilisateur par son pseudo

                    if ($utilisateur && password_verify($motDePasse, $utilisateur['uti_motdepasse'])) {
                        $verifierIdentite = [
                            "utiId" => $utilisateur['uti_id'],
                            "utiEmail" => $utilisateur['uti_email'],
                            "urlRedirection" => "/profil",
                            "envoyerCode" => true
                        ];

                        $_SESSION['verifierIdentite'] = $verifierIdentite;

                        // Redirige selon l'état d'activation du compte

                        if (intval($utilisateur['uti_compte_active']) === 0) {
                            header("location: /confirm");
                            exit();
                        } else {
                            connecter_utilisateur($_SESSION['verifierIdentite']['utiId']);
                            header("location: " . $_SESSION['verifierIdentite']['urlRedirection']);
                            exit();
                        }
                    } else {
                        $args['errorMessage'] = $formMessage['id-mdp-echec'];
                    }
                } catch (PDOException $e) {
                    gerer_exceptions($e);
                }
            }
        }
    }

    index($args);
}
