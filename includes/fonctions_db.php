<?php
require 'donnees_cachees.php';



function connexion_db(string $nomDuServeur, string $nomBDD, string $nomUtilisateur, $motDePasse): ?PDO
{

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
        return null;
    }

    return $pdo;
}
