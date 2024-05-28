<?php

// Fonction pour obtenir les configurations des champs pour le formulaire de contact
function obtenir_ChampsConfigsProfilReset(): array
{
    // Retourner un tableau associatif contenant les configurations des champs
    return [
        'email-reset' => [
            'requis' => true,
            'type' => 'email', // Vérification du format de l'email
            'mail_unique' => true // Vérifier que l'email est unique
        ],
        'password-confirm' => [
            'requis' => true,
            'minLength' => 8,
            'maxLength' => 72,
            'confirme' => 'motDePasse' // Vérification de la confirmation du mot de passe
        ],
        'password-reset' => [
            'requis' => true,
            'minLength' => 8,
            'maxLength' => 72
        ]
    ];
}
