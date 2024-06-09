<?php
// Fonction pour obtenir une route :
function obtenir_route(string $methode, string $chemin, string $controleur, string $action): array
{
    return [
        'methode' => $methode,
        'chemin' => $chemin,
        'controleur' => $controleur,
        'action' => $action,
    ];
}

function preparer_cheminPourComparaisonUrl(string $chemin, array $patterns): string
{
    // Effacer les slashs présent au début et en fin d'URL.
    $chemin = trim($chemin, '/');

    // Parcourir les patterns :
    foreach ($patterns as $marqueur => $pattern) {
        // Remplacer {marqueur} par l'expression régulière correspondant
        // ex.: "/admin-gestion-utilisateur/{id}" sera remplacé par "/admin-gestion-utilisateur/(\d+)".
        $chemin = str_replace('{' . $marqueur . '}', '(' . $pattern . ')', $chemin);
    }
    return $chemin;
}

// Fonction pour tester les routes :
function demarrer_routeur(array $routes, ?array $patterns = []): void
{
    // Récupérer les différents segments de l'URL dans "$_GET['url']".
    // Ceci est rendu possible grace à cette ligne dans le fichier ".htaccess" : RewriteRule ^(.*)$ public/index.php?url=$1 [QSA,L]
    // Éviter les injection de code à l'aide de la fonction filter_input :
    // "INPUT_GET" indique que la fonction doit récupérer la variable depuis la superglobale "$_GET".
    // "url" est le nom qu'on lui a attribué dans le fichier ".htaccess"
    // "FILTER_SANITIZE_URL" est le filtre utilisé pour nettoyer la variable en supprimant tous les caractères illégaux d'une URL.
    $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL) ?? "";

    // Effacer les slashs présent au début et en fin d'url.
    $url = trim($url, '/');

    // Récupérer la méthode de la requête serveur ("GET" ou "POST").
    $methode = $_SERVER['REQUEST_METHOD'];

    // Si la méthode est "POST", on vérifie si une méthode particulière a été ajoutée dans les champs cachés du formulaire ("PUT" ou "DELETE").
    $methode = $methode === 'POST' && isset($_POST['_methode']) ? strtoupper($_POST['_methode']) : $methode;

    // Traiter chaque route :
    foreach ($routes as $route) {
        // Préparer le chemin pour vérifier s'il correspond à l'URL et ce même si celui-ci est composé de marqueur (ex.: {id}).
        $chemin = preparer_cheminPourComparaisonUrl($route['chemin'], $patterns);

        // Vérifier si la route courante correspond au type de requête ainsi qu'à l'URL et récupérer les potentiels paramètres d'URL avec "$matches".
        if ($route['methode'] === $methode && preg_match("#^$chemin$#", $url, $matches)) {
            // Préparer les paramètres d'URL pour pouvoir les transmettre proprement au contrôleur.
            array_shift($matches);

            charger_controleur($route['controleur'], $route['action'], $matches);
            return;
        }
    }

    // Charger le contrôleur pour la page d'erreur 404.
    charger_controleur('Erreur404Controller', 'index');
}

function charger_controleur(string $controleur, string $action, ?array $urlParams = []): void
{
    // Charger le contrôleur.
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $controleur . '.php';

    // Appeler la fonction adéquate du contrôleur.
    $action(...$urlParams);
}
