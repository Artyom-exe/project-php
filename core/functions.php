<?php
function envoie_mail(array $valeursEchappees): void
{
    // Destinataire de l'email
    $destinataire = $valeursEchappees["user_mail"];
    // Sujet de l'email
    $sujet = "Formulaire de contact";
    // Construction du message en incluant les libellés des champs et leurs valeurs
    $message = "Nouveau message du formulaire :\n";
    foreach ($valeursEchappees as $champ => $valeur) {
        $message .= ucfirst($champ) . ": " . $valeur . "\n";
    }

    // Échapper les caractères spéciaux dans le sujet et le message
    $sujet = htmlspecialchars($sujet);
    $message = htmlspecialchars($message);

    // Envoyer l'email
    mail($destinataire, $sujet, $message);
};
