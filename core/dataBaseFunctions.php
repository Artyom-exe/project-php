<?php
require_once (__DIR__) . DS . 'config_helper.php';

// Fonction pour établir une connexion à la base de données
function connexion_db(): ?PDO
{
    $config = getConfig();

    $nomDuServeur = $config['nomDuServeur'];
    $nomUtilisateur = $config['nomUtilisateur'];
    $motDePasse = $config['motDePasse'];
    $nomBDD = $config['nomBDD'];

    try {
        // Création d'une nouvelle instance PDO pour la connexion à la base de données
        $pdo = new PDO("mysql:host=$nomDuServeur;dbname=$nomBDD;charset=utf8", $nomUtilisateur, $motDePasse);

        // Configuration du mode d'erreur pour générer des exceptions en cas d'erreur
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retourne l'objet PDO si la connexion réussit
        return $pdo;
    } catch (PDOException $e) {
        // Gestion sécurisée des exceptions en cas d'échec de la connexion
        gerer_exceptions($e);
        return null; // Retourne null en cas d'échec de la connexion
    }
}

// Fonction pour gérer les exceptions PDO
function gerer_exceptions($e)
{
    // Gestion sécurisée des exceptions PDO (par exemple, journalisation, affichage d'un message générique, etc.)
    echo "Une erreur s'est produite. Veuillez contacter l'administrateur du site.";
}

// Fonction pour vérifier si une valeur existe dans une colonne de la base de données
function verifier_valeurDbExiste(string $table, string $column, string $valeur)
{
    // Établir une connexion à la base de données
    $pdo = connexion_db();

    // Vérifier si la connexion à la base de données a réussi
    if (!$pdo) {
        return false; // Retourner false en cas d'échec de la connexion
    }

    try {
        // Utilisation d'une requête préparée pour éviter les injections SQL
        $requete = $pdo->prepare("SELECT COUNT(*) AS nbr FROM $table WHERE $column = ?");
        $requete->execute([$valeur]);

        // Récupérer le résultat de la requête
        $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

        // Vérifier si la valeur existe dans la base de données
        if (isset($utilisateur['nbr']) && $utilisateur['nbr'] > 0) {
            return true; // Retourner true si la valeur existe
        } else {
            return false; // Retourner false si la valeur n'existe pas
        }
    } catch (PDOException $e) {
        // Gestion sécurisée des exceptions en cas d'erreur lors de l'exécution de la requête
        gerer_exceptions($e);
        return false; // Retourner false en cas d'erreur
    }
}
