<?php
require_once dirname(__DIR__) . DS . 'private_data' . DS . 'dataConnectionDb.php';

function connexion_db(): ?PDO
{
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

        return $pdo;
    }
    // Capturer les exceptions en cas d'erreur de connexion :
    catch (PDOException $e) {
        // Afficher les potentielles erreurs rencontrées lors de la tentative de connexion à la base de données.
        // Attention, les informations affichées ici pouvant être sensibles, cet affichage est uniquement destiné à la phase de développement.
        echo "Erreur d'exécution de requête : " . $e->getMessage() . PHP_EOL;
    }
}

function gerer_exceptions($e)
{
    echo "Erreur d'exécution de requête : " . $e->getMessage() . PHP_EOL;
}

function verifier_valeurDbExiste(string $table, string $column, string $valeur)
{
    $pdo = connexion_db();

    try {
        $requete = "SELECT COUNT(*) AS nbr FROM $table  WHERE $column = '" . $valeur . "'";

        // Exécuter la requête.
        $stmt = $pdo->query($requete);
    } catch (PDOException $e) {
        echo "Une erreur s'est produite";
    }


    if (isset($stmt) && $stmt !== false) {
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($utilisateur['nbr']) && ($utilisateur['nbr'] !== 0)) {
            return true;
        } else {
            return false;
        }
    }
}
