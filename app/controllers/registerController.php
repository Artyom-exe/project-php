<?php
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'formGestion.php';
require_once dirname(__DIR__) . DS . 'models' . DS . 'authentificationModel.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'messagesGestion.php';
require_once dirname(__DIR__, 2) . DS . 'private_data' . DS . 'dataConnectionDb.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'dataBaseFunctions.php';

$errors = [];
$valeursEchappees = [];

$pdo = connexion_db($nomDuServeur, $nomBDD, $nomUtilisateur, $motDePasse);

$champsConfig = obtenir_ChampsConfigsAuthentification($pdo);

$formMessage = $formMessage = importer_messages('formMessages.json');


if (($_SERVER["REQUEST_METHOD"] === "POST")) {

    gestion_formulaire($formMessage, $champsConfig, $errors, $valeursEchappees);

    // Après la validation du formulaire et avant d'afficher le message de succès

    if (empty($errors)) {

        $pdo = connexion_db($nomDuServeur, $nomBDD, $nomUtilisateur, $motDePasse);

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
    }
} else {
    echo "<div style= 'text-align: center; font-size: 1.2em; color: red; font-weight: bold; margin: 10px;'> " . $formMessage["envoi_echec"] . "</div>";
}
