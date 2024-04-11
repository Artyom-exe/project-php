<?php
require_once dirname(__DIR__, 2) . DS . "core" . DS . "dataBaseFunctions.php";
require_once dirname(__DIR__, 2) . DS . "private_data" . DS . "dataConnectionDb.php";

$pdo = connexion_db($nomDuServeur, $nomBDD, $nomUtilisateur, $motDePasse);

function obtenir_ChampsConfigsAuthentification($register = true): array
{

    $configs = [
        'pseudo' => [
            'requis' => true,
            'minLength' => 2,
            'maxLength' => 255,
            'pseudo_unique' => true
        ],
        'motDePasse' => [
            'requis' => true,
            'minLength' => 8,
            'maxLength' => 72
        ]
    ];

    if ($register) {

        $configs['pseudo'] = [
            'pseudo_unique' => true,
            'requis' => true,
            'minLength' => 2,
            'maxLength' => 255,
        ];

        $configs['motDePasse_confirmation'] = [
            'requis' => true,
            'minLength' => 8,
            'maxLength' => 72,
            'confirme' => 'motDePasse'
        ];

        $configs['email'] = [
            'requis' => true,
            'type' => 'email',
            'mail_unique' => true
        ];
    }

    return $configs;
}
