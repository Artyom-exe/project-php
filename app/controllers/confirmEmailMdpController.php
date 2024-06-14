<?php
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'profilGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'formGestion.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'authentificationGestion.php';
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'confirmModel.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'messagesGestion.php';

GestionSession();

// Génération du token CSRF s'il n'existe pas
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function obtenir_pageInfos()
{
    return [
        'vue' => 'confirm-email-mdp',
        'titre' => 'Confirmation',
        'description' => 'Confirmez votre changement d\'email ou de mot de passe.'
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
    // Vérifiez si l'utilisateur est connecté
    if (!isset($_SESSION['id']) || !est_connecte($_SESSION['id'])) {
        header("Location: /connexion");
        exit();
    }

    // Assurez-vous que les données de session nécessaires sont présentes
    if (!isset($_SESSION['new_email']) && !isset($_SESSION['new_password'])) {
        header("Location: /profil");
        exit();
    }

    $sujet = "Votre code d'activation";
    $destinataire = $_SESSION['utilisateur']['uti_email'];
    $formMessage = importer_messages('formMessages.json');

    // Générer et envoyer le code d'activation s'il n'est pas déjà défini dans la session
    if (!isset($_SESSION['code']) || empty($_SESSION['code'])) {
        $args['successMessage'] = envoyer_code_activation($destinataire, $sujet, $formMessage);
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
        'successMessage' => '',
        'valeursEchappees' => []
    ];

    $champsConfig = obtenir_ChampsConfigsModel();
    $formMessage = importer_messages('formMessages.json');
    $sujet = "Votre code d'activation";
    $destinataire = $_SESSION['utilisateur']['uti_email'];

    // Gérer les requêtes POST
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
                        // Code correct, procéder à la mise à jour de l'email ou du mot de passe
                        if (isset($_SESSION['new_email'])) {
                            try {
                                $pdo = connexion_db();
                                $stmt = $pdo->prepare("UPDATE t_utilisateur_uti SET uti_email = :email WHERE uti_id = :id");
                                $stmt->bindValue(':email', $_SESSION['new_email'], PDO::PARAM_STR);
                                $stmt->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
                                $stmt->execute();
                                $_SESSION['success'] = $formMessage['mail-change'];
                            } catch (PDOException $e) {
                                $args['error'] = $formMessage['maj-failed-email'] . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
                            }
                            unset($_SESSION['new_email']);
                            unset($_SESSION['code']);
                        }

                        if (isset($_SESSION['new_password'])) {
                            try {
                                $pdo = connexion_db();
                                $stmt = $pdo->prepare("UPDATE t_utilisateur_uti SET uti_motdepasse = :mot_de_passe WHERE uti_id = :id");
                                $stmt->bindValue(':mot_de_passe', password_hash($_SESSION['new_password'], PASSWORD_BCRYPT), PDO::PARAM_STR);
                                $stmt->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
                                $stmt->execute();
                                $_SESSION['success'] = $formMessage['password-change-succes'];
                            } catch (PDOException $e) {
                                $args['error'] = $formMessage['maj-failed-password'] . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
                            }
                            unset($_SESSION['new_password']);
                            unset($_SESSION['code']);
                        }

                        // Redirection vers le profil après succès
                        header("Location: /profil");
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
