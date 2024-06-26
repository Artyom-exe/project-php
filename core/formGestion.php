<?php

require_once dirname(__DIR__) . DS . "core" . DS . "dataBaseFunctions.php";

function gestion_formulaire(array $formMessage, array $champsConfig): array
{
    $errors = [];
    $valeursEchappees = [];

    // Parcourt tous les champs configurés
    foreach ($champsConfig as $nomChamps => $regles) {
        // Récupère la valeur du champ et la nettoie
        $valeur = isset($_POST[$nomChamps]) ? trim($_POST[$nomChamps]) : '';
        // Échappe les caractères spéciaux pour éviter les attaques XSS
        $valeursEchappees[$nomChamps] = htmlentities($valeur);

        // Vérifie les règles de validation pour chaque champ
        if (isset($regles['requis']) && $regles['requis'] && empty($valeur)) {
            $errors[$nomChamps] = $formMessage["requis"];
        } elseif (isset($regles["minLength"]) && strlen($valeur) < $regles["minLength"]) {
            $errors[$nomChamps] = str_replace("%0%", $regles["minLength"], $formMessage["minLength"]);
        } elseif (isset($regles["maxLength"]) && strlen($valeur) > $regles["maxLength"]) {
            $errors[$nomChamps] = str_replace("%0%", $regles["maxLength"], $formMessage["maxLength"]);
        } elseif (isset($regles['type']) && $regles["type"] === "email" && !filter_var($valeur, FILTER_VALIDATE_EMAIL)) {
            $errors[$nomChamps] = $formMessage["email"];
        } elseif (isset($regles['confirme']) && (!isset($_POST[$regles['confirme']]) || $valeur !== ($_POST[$regles['confirme']]))) {
            $errors[$nomChamps] = $formMessage["confirme"];
        } elseif (isset($regles['mail_unique']) && $regles['mail_unique'] === true && verifier_valeurDbExiste('t_utilisateur_uti', 'uti_email', $valeur)) {
            $errors[$nomChamps] = $formMessage["mail_existe"];
        } elseif (isset($regles['pseudo_unique']) && $regles['pseudo_unique'] === true && verifier_valeurDbExiste('t_utilisateur_uti', 'uti_pseudo', $valeur)) {
            $errors[$nomChamps] = $formMessage["pseudo_existe"];
        }
    }

    // Retourne un tableau contenant les erreurs de validation et les valeurs échappées des champs

    return [
        'errors' => $errors,
        'valeursEchappees' => $valeursEchappees
    ];
}
