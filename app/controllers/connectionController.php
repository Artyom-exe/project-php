<?php
// Inclusion des fichiers nécessaires
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'profilGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'formGestion.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'authentificationModel.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'profilGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'authentificationGestion.php';

GestionSession();

// Génération du token CSRF s'il n'existe pas
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function obtenir_pageInfos()
{
    return [
        'vue' => 'connection',
        'titre' => 'Connexion',
        'description' => '...'
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
    $args = [
        'errors' => [],
        'errorMessage' => '',
        'valeursEchappees' => []
    ];

    $champsConfig = obtenir_ChampsConfigsAuthentification(false);
    $formMessage = importer_messages('formMessages.json');

    $limiteTemps = 1; // Limite de temps en secondes entre les requêtes
    $tempsActuel = time();


    // Vérifiez si la session a déjà enregistré une requête précédente et le temps écoulé
    if (isset($_SESSION['dernierAcces']) && ($tempsActuel - $_SESSION['dernierAcces'] < $limiteTemps)) {
        $tempsRestant = $limiteTemps - ($tempsActuel - $_SESSION['dernierAcces']);
        $args['errorMessage'] = "Veuillez attendre $tempsRestant secondes avant de refaire une requête.";
        index($args);
        return;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $args['errors']['csrf_token'] = "Token CSRF invalide.";
        } else {
            $args = gestion_formulaire($formMessage, $champsConfig);

            if (empty($args['errors'])) {
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
                        $verifierIdentite = [
                            "utiId" => $utilisateur['uti_id'],
                            "utiEmail" => $utilisateur['uti_email'],
                            "urlRedirection" => "/profil",
                            "envoyerCode" => true
                        ];

                        $_SESSION['verifierIdentite'] = $verifierIdentite;

                        // Met à jour le temps de la dernière requête réussie
                        $_SESSION['dernierAcces'] = $tempsActuel;

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
