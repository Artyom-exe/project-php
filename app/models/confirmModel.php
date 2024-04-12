<?php

function obtenir_ChampsConfigsModel(): array
{
    return [
        'form_code' => [
            'verification_code' => [
                'requis' => true,
                'minLength' => 5,
                'maxLength' => 5
            ],
        ],
        'form_request' => [
            'email' => [
                'requis' => true,
                'type' => 'email'
            ],
        ]
    ];
}
