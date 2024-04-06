<?php
require_once dirname(__DIR__, 2) . DS . "core" . DS . "dataBaseFunctions.php";
require_once dirname(__DIR__, 2) . DS . "private_data" . DS . "dataConnectionDb.php";

connexion_db($nomDuServeur, $nomBDD, $nomUtilisateur, $motDePasse);

function obtenir_ChampsConfigsAuthentification($pdo, $register = true): array
{

    $configs = [
        'pseudo' => [
            'requis' => true,
            'minLength' => 2,
            'maxLength' => 255
        ],
        'motDePasse' => [
            'requis' => true,
            'minLength' => 8,
            'maxLength' => 72
        ]
    ];

    if ($register) {
        $configs['pseudo'] = [
            'existeDeja' => verifier_valeurDbExiste($pdo, 't_utilisateur_uti', 'uti_pseudo',  $_POST['pseudo'])
        ];

        $configs['motDePasse_confirmation'] = [
            'requis' => true,
            'minLength' => 8,
            'maxLength' => 72,
            'confirme' => 'motDePasse'
        ];

        $configs['email'] = [
            'requis' => true,
            'type' => 'email'
        ];
    }

    return $configs;
}
