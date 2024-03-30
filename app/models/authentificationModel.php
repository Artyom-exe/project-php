<?php

function obtenir_ChampsConfigsAuthentification(): array
{

    return [
        'pseudo' => [
            'requis' => true,
            'minLength' => 2,
            'maxLength' => 255
        ],
        'motDePasse' => [
            'requis' => true,
            'minLength' => 8,
            'maxLength' => 72
        ],
        'motDePasse_confirmation' => [
            'requis' => true,
            'minLength' => 8,
            'maxLength' => 72,
            'confirme' => 'motDePasse'
        ],
        'email' => [
            'requis' => true,
            'type' => 'email'
        ]
    ];
}
