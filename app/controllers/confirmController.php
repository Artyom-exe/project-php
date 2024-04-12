<?php
require_once dirname(__DIR__, 1) . DS . 'models' . DS . 'confirmModel.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'formGestion.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'authentificationGestion.php';

if (!isset($_SESSION['verifierIdentite'])) {
    header("location: /index.php");
    exit();
};

print_r($_SESSION['code']);
if (!isset($_SESSION['code']) && $_SESSION['verifierIdentite']['envoyerCode'] === true) {

    $_SESSION['code'] = rand(10000, 99999);

    $sujet = "Votre code d'activation";
    $destinataire = $_SESSION['verifierIdentite']['utiEmail'];

    try {
        $pdo = connexion_db();

        $requete = "UPDATE t_utilisateur_uti SET uti_code_activation = :codeActivation";
        $stmt = $pdo->prepare($requete);
        $stmt->bindValue(':codeActivation', $_SESSION['code'], PDO::PARAM_STR);

        $stmt->execute();
    } catch (\PDOException $e) {
        gerer_exceptions($e);
    }

    mail($destinataire, $sujet, $_SESSION['code']);
    echo "<div style= 'text-align: center; font-size: 1.2em; color: green; font-weight: bold; margin: 10px;'> Un code d'activation vous a été envoyé à l'adresse suivante: " . $destinataire . "</div>";
}

$errors = [];
$valeursEchappees = [];

$champsConfig = obtenir_ChampsConfigsModel();

$formMessage = importer_messages('formMessages.json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['form_code'])) {

        gestion_formulaire($formMessage, $champsConfig['form_code'], $errors, $valeursEchappees);
    }

    if (empty($errors)) {

        if ($valeursEchappees['verification_code'] == $_SESSION['code']) {

            try {
                $pdo = connexion_db();

                $requete = "UPDATE t_utilisateur_uti SET uti_compte_active = :compteActive";
                $stmt = $pdo->prepare($requete);
                $stmt->bindValue(':compteActive', 1, PDO::PARAM_STR);

                $stmt->execute();
            } catch (\PDOException $e) {
                gerer_exceptions($e);
            }
            connecter_utilisateur($_SESSION['verifierIdentite']['utiId']);
            header("location: " . $_SESSION['verifierIdentite']['urlRedirection']);
            $temp = $_SESSION['verifierIdentite'];
            unset($temp);
            exit();
        } else {
            echo "YES";
        }
    } else {
        echo "<div style= 'text-align: center; font-size: 1.2em; color: red; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_echec"] . "</div>";
    }
}
