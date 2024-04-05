<?php

// Définir un raccourci pour DIRECTORY_SEPARATOR.
define('DS', DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . DS . "core" . DS . "profilGestion.php";

GestionSession();

// Permet de distinguer le mode développement du mode production.
define('DEV_MODE', true);

// Définir BASE_URL en fonction du contexte du serveur :
//  - projet situé à la racine: define('BASE_URL', "");
//  - projet situé un/des sous-dossier(s) : define('BASE_URL', "/nom_du_sous_dossier");
// Donc dans ce cas si le projet n'étant pas situé dans un sous-dossier,
// si j'utilise un hôte virtuel (par ex.: 9_gestionnaire_de_formulaire.test) : define('BASE_URL', "");
// alors que si j'utilise localhost (par ex.: localhost/9_gestionnaire_de_formulaire/) : define('BASE_URL', "9_gestionnaire_de_formulaire");
define('BASE_URL', '');

// Définir la langue.
define('LANGUE', 'fr');
