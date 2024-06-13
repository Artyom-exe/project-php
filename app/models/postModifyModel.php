<?php

function obtenir_ChampsConfigsModifyPost(): array
{

    return [
        'post-title-modify' => [
            'requis' => true,
            'maxLength' => 255,
        ],
        'post-content-modify' => [
            'requis' => true,
            'maxLength' => 255
        ]
    ];
}
