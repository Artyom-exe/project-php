<?php
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'formGestion.php';
require_once dirname(__DIR__, 1) . DS . 'models' . DS . 'authentificationModel.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'profilGestion.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'authentificationGestion.php';

if (isset($_SESSION['id']) && est_connecte($_SESSION['id'])) {
    header("location: /profil.php");
}

$errors = [];
$valeursEchappees = [];

$champsConfig = obtenir_ChampsConfigsAuthentification(false);

$formMessage = importer_messages('formMessages.json');

if (($_SERVER["REQUEST_METHOD"] === "POST")) {

    gestion_formulaire($formMessage, $champsConfig, $errors, $valeursEchappees);

    if (empty($errors)) {

        try {

            $pdo = connexion_db();

            $pseudo = $_POST['pseudo'];
            $motDePasse = $_POST['motDePasse'];

            $requete = "SELECT * FROM t_utilisateur_uti WHERE uti_pseudo = :pseudo";

            $stmt = $pdo->prepare($requete);

            $stmt->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);

            $estValide = $stmt->execute();
        } catch (PDOException $e) {
            gerer_exceptions($e);
        }

        if (isset($estValide) && $estValide !== false) {

            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($utilisateur && password_verify($motDePasse, $utilisateur['uti_motdepasse'])) {

                $verifierIdentite = [
                    "utiId" => $utilisateur['uti_id'],
                    "utiEmail" => $utilisateur['uti_email'],
                    "urlRedirection" => "/profil.php",
                    "envoyerCode" => true
                ];

                $_SESSION['verifierIdentite'] = $verifierIdentite;

                if (!isset($utilisateur['uti_code_activation'])) {
                    header("location: /confirm.php");
                    exit();
                } else {
                    connecter_utilisateur($_SESSION['verifierIdentite']['utiId']);
                    header("location: " . $_SESSION['verifierIdentite']['urlRedirection']);
                    exit();
                }
            } else {
                echo "<div style= 'text-align: center; font-size: 1.2em; color: red; font-weight: bold; margin: 10px;'> " . $formMessage["id-mdp-echec"] . "</div>";
            }
        } else {
            echo "<div style= 'text-align: center; font-size: 1.2em; color: red; font-weight: bold; margin: 10px;'> " . $formMessage["connection_echec"] . "</div>";
        }
    }
}
