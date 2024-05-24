<?php
function envoie_mail(array $valeursEchappees): void
{

    $destinataire = "th.lambot@hotmail.fr";
    $sujet = "Formulaire";
    $message = implode("\r\n", $valeursEchappees);

    if (mail($destinataire, $sujet, $message)) {
        echo
        "<div style= 'text-align: center; font-size: 1.2em; color: green; font-weight: bold; margin: 10px;'> " . "Le courriel a été envoyé avec succès." . "</div>";
    } else {
        echo
        "<div style= 'text-align: center; font-size: 1.2em; color: green; font-weight: bold; margin: 10px;'> " . "L'envoi du courriel a échoué." . "</div>";
    }
};
