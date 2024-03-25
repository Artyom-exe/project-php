<?php

$errors = [];
$valeursEchappees = [];

$champsConfig = [
    'inscription_pseudo' => [
        'requis' => true,
        'minLength' => 2,
        'maxLength' => 255
    ],
    'inscription_motDePasse' => [
        'requis' => true,
        'minLength' => 8,
        'maxLength' => 72
    ],
    'inscription_motDePasse_confirmation' => [
        'requis' => true,
        'minLength' => 8,
        'maxLength' => 72
    ],
    'inscription_email' => [
        'requis' => true,
        'type' => 'email'
    ]
];

$formMessage = [
    "requis" => "Ce champs est requis",
    "email" => "Veuillez entrer une adresse mail valide!",
    "minMaxLength" => "Ce champs doit comprendre entre %0% et %1% caractères",
    "minLength" => "Ce champs doit avoir au plus %0% caractères",
    "maxLength" => "Ce champs doit avoir au moins %0% caractères",
    "envoi_echec" => "Un problème est survenu",
    "envoi_succes" => "Vous êtes inscrit"
];

if (($_SERVER["REQUEST_METHOD"] === "POST")) {

    foreach ($champsConfig as $nomChamps => $regles) {

        $inscriptionPseudo = trim(htmlentities($_POST["inscription_pseudo"]));
        $valeursEchappees['inscription_pseudo'] = $inscriptionPseudo;

        if ((empty($inscriptionPseudo)) && ($champsConfig["inscription_pseudo"]["requis"])) {
            $errors['inscription_pseudo'] = $formMessage["requis"];
        } elseif (strlen($inscriptionPseudo) < $champsConfig["inscription_pseudo"]["minLength"]) {
            $errors['inscription_pseudo'] = $formMessage["maxLength"];
        } elseif (strlen($inscriptionPseudo) > $champsConfig["inscription_pseudo"]["maxLength"]) {
            $errors['inscription_pseudo'] = $formMessage["minLength"];
        }

        $inscriptionMotDePasse = trim(htmlentities($_POST["inscription_motDePasse"]));
        $valeursEchappees['inscription_motDePasse'] = $inscriptionMotDePasse;

        if ((empty($inscriptionMotDePasse)) && ($champsConfig["inscription_motDePasse"]["requis"])) {
            $errors['inscription_motDePasse'] = $formMessage["requis"];
        } elseif (strlen($inscriptionMotDePasse) < $champsConfig["inscription_motDePasse"]["minLength"]) {
            $errors['inscription_motDePasse'] = $formMessage["maxLength"];
        } elseif (strlen($inscriptionMotDePasse) > $champsConfig["inscription_motDePasse"]["maxLength"]) {
            $errors['inscription_motDePasse'] = $formMessage["minLength"];
        }

        $confInscriptionMdp = trim(htmlentities($_POST["inscription_motDePasse_confirmation"]));
        $valeursEchappees['inscription_motDePasse_confirmation'] = $confInscriptionMdp;

        if ((empty($confInscriptionMdp)) && ($champsConfig["inscription_motDePasse_confirmation"]["requis"])) {
            $errors['inscription_motDePasse_confirmation'] = $formMessage["requis"];
        } elseif (strlen($confInscriptionMdp) < $champsConfig["inscription_motDePasse_confirmation"]["minLength"]) {
            $errors['inscription_motDePasse_confirmation'] = $formMessage["maxLength"];
        } elseif (strlen($confInscriptionMdp) > $champsConfig["inscription_motDePasse_confirmation"]["maxLength"]) {
            $errors['inscription_motDePasse_confirmation'] = $formMessage["minLength"];
        }

        $inscription_email = trim(htmlentities($_POST["inscription_email"]));
        $valeursEchappees['inscription_email'] = $inscription_email;

        if (empty($inscription_email)) {
            $errors['inscription_email'] = $formMessage["requis"];
        } elseif (!filter_var($inscription_email, FILTER_VALIDATE_EMAIL)) {
            $errors['inscription_email'] = $formMessage["email"];
        }
    }

    if (empty($errors)) {

        // Après la validation du formulaire et avant d'afficher le message de succès
        if (empty($errors)) {


            $nomDuServeur = "localhost";
            $nomUtilisateur = "root";
            $motDePasse = "";
            $nomBDD = "project_php";

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
            $pseudo = $valeursEchappees['inscription_pseudo'];
            $motDePasse = password_hash($valeursEchappees['inscription_motDePasse'], PASSWORD_DEFAULT); // Hashage du mot de passe
            $email = $valeursEchappees['inscription_email'];

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
}
