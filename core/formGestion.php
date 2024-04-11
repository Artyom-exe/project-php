<?php

require_once dirname(__DIR__) . DS . "core" . DS . "dataBaseFunctions.php";

function gestion_formulaire(array $formMessage, array $champsConfig, array &$errors, array &$valeursEchappees): void
{

    foreach ($champsConfig as $nomChamps => $regles) {

        $valeur = trim(htmlentities($_POST[$nomChamps]));
        echo $valeur;
        $valeursEchappees[$nomChamps] = $valeur;

        if (isset($regles['requis']) && $regles['requis'] && empty($valeur)) {
            $errors[$nomChamps] = $formMessage["requis"];
        } elseif (isset($regles["minLength"]) && strlen($valeur) < $regles["minLength"]) {
            $errors[$nomChamps] = str_replace("%0%", $regles["minLength"], $formMessage["minLength"]);
        } elseif (isset($regles["maxLength"]) && strlen($valeur) > $regles["maxLength"]) {
            $errors[$nomChamps] = str_replace("%0%", $regles["maxLength"], $formMessage["maxLength"]);
        } elseif (isset($regles['type']) && $regles["type"] === "email" && !filter_var($valeur, FILTER_VALIDATE_EMAIL)) {
            $errors[$nomChamps] = $formMessage["email"];
        } elseif (isset($regles['confirme']) && $valeur !== $_POST[$regles['confirme']]) {
            $errors[$nomChamps] = $formMessage["confirme"];
        } elseif (isset($regles['mail_unique']) && $regles['mail_unique'] === true && verifier_valeurDbExiste('t_utilisateur_uti', 'uti_email', $_POST['email'])) {
            $errors[$nomChamps] = $formMessage["mail_existe"];
        } elseif (isset($regles['pseudo_unique']) && $regles['pseudo_unique'] === true && verifier_valeurDbExiste('t_utilisateur_uti', 'uti_pseudo', $_POST['pseudo'])) {
            $errors[$nomChamps] = $formMessage["pseudo_existe"];
        }
    }
}
