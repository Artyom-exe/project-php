<?php
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'formGestion.php';
require_once dirname(__DIR__, 1) . DS . 'models' . DS . 'authentificationModel.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DS . 'private_data' . DS . 'dataConnectionDb.php';

$errors = [];
$valeursEchappees = [];

$champsConfig = obtenir_ChampsConfigsAuthentification();

$formMessage = $formMessage = importer_messages('formMessages.json');


if (($_SERVER["REQUEST_METHOD"] === "POST")) {

    gestion_formulaire($formMessage, $champsConfig, $errors, $valeursEchappees);

    // Après la validation du formulaire et avant d'afficher le message de succès

    if (empty($errors)) {

        // Tenter d'établir une connexion à la base de données :
        try {
            // Instancier une nouvelle connexion.
            $pdo = new PDO("mysql:host=$nomDuServeur;dbname=$nomBDD;charset=utf8", $nomUtilisateur, $motDePasse);

            // Définir le mode d'erreur sur "exception".
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        // Capturer les exceptions en cas d'erreur de connexion :
        catch (PDOException $e) {
            // Afficher les potentielles erreurs rencontrées lors de la tentative de connexion à la base de données.
            // Attention, les informations affichées ici pouvant être sensibles, cet affichage est uniquement destiné à la phase de développement.
            echo "Erreur d'exécution de requête : " . $e->getMessage() . PHP_EOL;
        }

        // Création d'un nouvel utilisateur dans la base de données
        $pseudo = $valeursEchappees['pseudo'];
        $motDePasse = password_hash($valeursEchappees['motDePasse'], PASSWORD_DEFAULT); // Hashage du mot de passe
        $email = $valeursEchappees['email'];

        // Requête SQL d'insertion dans la base de données
        $requete = "INSERT INTO t_utilisateur_uti (uti_pseudo, uti_motdepasse, uti_email) VALUES (:pseudo, :mot_de_passe, :email)";

        try {
            // Préparation de la requête
            $stmt = $pdo->prepare($requete);

            // Liaison des paramètres
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':mot_de_passe', $motDePasse);
            $stmt->bindParam(':email', $email);

            // Exécution de la requête
            $stmt->execute();

            // Affichage du message de succès
            echo "<div style= 'text-align: center; font-size: 1.2em; color: green; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_succes"] . "</div>";

            $valeursEchappees = [];
        } catch (PDOException $e) {
            // Affichage de l'erreur en cas d'échec de l'insertion
            echo "Erreur lors de l'insertion dans la base de données : " . $e->getMessage();
        }
    } else {
        echo "<div style= 'text-align: center; font-size: 1.2em; color: red; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_echec"] . "</div>";
    }
}
