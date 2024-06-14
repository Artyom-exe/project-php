<?php
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'dataBaseFunctions.php';


function obtenir_pageInfos()
{
    return [
        'vue' => 'accueil',
        'titre' => 'Accueil',
        'description' => 'Accueil du site'
    ];
}

function index()
{
    try {
        // Connexion à la base de données
        $pdo = connexion_db();

        // Préparation de la requête SQL pour récupérer les posts des utilisateurs
        $requete = "SELECT uti_pseudo, pos_title, pos_content
                    FROM t_utilisateur_uti
                    INNER JOIN p_posts_pos ON uti_id=pos_uti_id";

        $stmt = $pdo->prepare($requete);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des informations des posts utilisateurs
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($posts) {
            // Stockage des informations de l'utilisateur dans une variable de session
            $args['posts'] = $posts;
        } else {
            throw new Exception("Utilisateur non trouvé.");
        }
    } catch (PDOException $e) {
        // Gestion des exceptions PDO
        $args['error'] = "Erreur PDO : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    } catch (Exception $e) {
        // Gestion des autres exceptions
        $args['error'] = "Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
    // Inclure la vue avec les arguments
    afficher_vue(obtenir_pageInfos(), 'index', $args);
}

index();
