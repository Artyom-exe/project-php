<?php

require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'dataBaseFunctions.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'authentificationGestion.php';

if (!isset($_SESSION['id']) && !est_connecte($_SESSION['id'])) {
    header("location: /connection.php");
}

try {

    $pdo = connexion_db();

    $requete = "SELECT * FROM t_utilisateur_uti WHERE uti_id = :id";

    $stmt = $pdo->prepare($requete);

    $stmt->bindValue(':id', $_SESSION['id'], PDO::PARAM_STR);

    $stmt->execute();

    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    gerer_exceptions($e);
}
