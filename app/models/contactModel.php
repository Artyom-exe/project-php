<?php

// Fonction pour obtenir les configurations des champs pour le formulaire de contact
function obtenir_ChampsConfigsContact(): array
{
    // Retourner un tableau associatif contenant les configurations des champs
    return [
        'user_lastname' => [
            'requis' => true,
            'minLength' => 2,
            'maxLength' => 255
        ],
        'user_firstname' => [
            'requis' => false,
            'minLength' => 2,
            'maxLength' => 255
        ],
        'user_mail' => [
            'requis' => true,
            'type' => 'email'
        ],
        'user_message' => [
            'requis' => true,
            'minLength' => 10,
            'maxLength' => 3000
        ]
    ];
}
