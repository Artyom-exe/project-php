<?php

if (!function_exists('nav_item')) {

    function nav_item(string $lien, string $pageTitre): string
    {
        // Échapper les valeurs pour éviter les attaques XSS
        $lien = htmlspecialchars($lien);
        $pageTitre = htmlspecialchars($pageTitre);

        // Initialiser la classe à une chaîne vide
        $classe = '';

        // Ajouter la classe 'active' si le lien correspond à la page actuelle
        $classe = ($_SERVER['SCRIPT_NAME'] === $lien) ? 'active' : '';

        // Générer l'élément de menu de navigation
        return '<li class="' . $classe . '">
                    <a href="' . $lien . '">' . $pageTitre . '</a> 
                </li>';
    }
}
