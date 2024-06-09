<?php
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'init.php';


function obtenir_pageInfos()
{
    return [
        'vue' => 'accueil',
        'titre' => 'Accueil',
        'description' => '...'
    ];
}

function index()
{
    // Inclure la vue avec les arguments
    afficher_vue(obtenir_pageInfos(), 'index');
}

index();
