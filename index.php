<?php
define('DS', DIRECTORY_SEPARATOR);

require_once __DIR__ . DS . 'core' . DS . 'routeur.php';
require_once __DIR__ . DS . 'core' . DS . 'profilGestion.php';
require_once __DIR__ . DS . 'core' . DS . 'gestionVue.php';

define('DEV_MODE', true);
define('BASE_URL', '');
define('LANGUE', 'fr');

GestionSession();

$routes = [
    obtenir_route('GET', '/', 'accueilController', 'index'),
    obtenir_route('GET', '/inscription', 'registerController', 'index'),
    obtenir_route('POST', '/inscription', 'registerController', 'insert'),
    obtenir_route('GET', '/connexion', 'connectionController', 'index'),
    obtenir_route('POST', '/connexion', 'connectionController', 'insert'),
    obtenir_route('GET', '/profil', 'profilController', 'index'),
    obtenir_route('POST', '/profil', 'profilController', 'insert'),
    obtenir_route('GET', '/logout', 'logoutController', 'logout'),
    obtenir_route('GET', '/contact', 'contactController', 'index'),
    obtenir_route('POST', '/contact', 'contactController', 'insert'),
    obtenir_route('GET', '/confirm', 'confirmController', 'index'),
    obtenir_route('POST', '/confirm', 'confirmController', 'insert'),
    obtenir_route('GET', '/confirm-email-mdp', 'confirmEmailMdpController', 'index'),
    obtenir_route('POST', '/confirm-email-mdp', 'confirmEmailMdpController', 'insert')
];

demarrer_routeur($routes);
