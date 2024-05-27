<?php

// Définition de la fonction pour obtenir les configurations des champs du modèle
function obtenir_ChampsConfigsModel(): array
{
    // Retourner un tableau associatif contenant les configurations des champs
    return [
        'form_code' => [ // Champ pour le code de vérification du formulaire
            'verification_code' => [
                'requis' => true,
                'minLength' => 5,
                'maxLength' => 5
            ],
        ]
    ];
}
