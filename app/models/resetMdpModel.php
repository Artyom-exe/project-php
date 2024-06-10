<?php

function obtenir_ChampsConfigsMdpReset(): array
{

    return [
        'password-confirm' => [
            'requis' => true,
            'minLength' => 8,
            'maxLength' => 72,
            'confirme' => 'password-reset' // Vérification de la confirmation du mot de passe
        ],
        'password-reset' => [
            'requis' => true,
            'minLength' => 8,
            'maxLength' => 72
        ]
    ];
}
