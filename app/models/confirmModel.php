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
        ]
    ];
}
