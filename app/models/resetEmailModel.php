<?php

// Fonction pour obtenir les configurations des champs pour le formulaire de contact
function obtenir_ChampsConfigsEmailReset(): array
{
    // Retourner un tableau associatif contenant les configurations des champs
    return [
        'email-reset' => [
            'requis' => true,
            'type' => 'email', // Vérification du format de l'email
            'mail_unique' => true // Vérifier que l'email est unique
        ]
    ];
}
