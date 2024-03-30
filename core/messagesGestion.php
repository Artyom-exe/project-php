<?php

function importer_messages(string $nomFichier): array
{
    // Récupérer la langue du site.
    $langue = defined('LANGUE') ? strtolower(LANGUE) : 'fr';

    // Charger les messages liés aux formulaires à partir du fichier 'form_messages.json et le convertir en tableau associatif :
    $messages = file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config'  . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $langue . DIRECTORY_SEPARATOR . $nomFichier);
    $messages = json_decode($messages, true);
    return $messages;
}
