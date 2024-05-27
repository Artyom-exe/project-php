<?php

function obtenir_ChampsConfigsAuthentification($register = true): array
{
    // Tableau des configurations par défaut pour l'authentification
    $configs = [
        'pseudo' => [
            'requis' => true,
            'minLength' => 2,
            'maxLength' => 255,
        ],
        'motDePasse' => [
            'requis' => true,
            'minLength' => 8,
            'maxLength' => 72
        ]
    ];

    // Si c'est pour un enregistrement, ajouter des configurations supplémentaires
    if ($register) {
        $configs['pseudo'] = [
            'pseudo_unique' => true, // Vérifier que le pseudo est unique
            'requis' => true,
            'minLength' => 2,
            'maxLength' => 255,
        ];

        $configs['motDePasse_confirmation'] = [
            'requis' => true,
            'minLength' => 8,
            'maxLength' => 72,
            'confirme' => 'motDePasse' // Vérification de la confirmation du mot de passe
        ];

        $configs['email'] = [
            'requis' => true,
            'type' => 'email', // Vérification du format de l'email
            'mail_unique' => true // Vérifier que l'email est unique
        ];
    }

    return $configs;
}
