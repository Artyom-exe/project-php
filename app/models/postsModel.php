<?php

function obtenir_ChampsConfigsPost(): array
{

    return [
        'post-title' => [
            'requis' => true,
            'maxLength' => 255,
        ],
        'post-content' => [
            'requis' => true,
            'maxLength' => 255
        ]
    ];
}
