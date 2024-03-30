<?php

if (!function_exists('nav_item')) {

    function nav_item(string $lien, string $pageTitre): string
    {

        $classe = '';

        ($_SERVER['SCRIPT_NAME'] === $lien) ? $classe .= 'active' : '';

        return <<<HTML
        <li class= "$classe">
            <a href="$lien">$pageTitre</a> 
        </li>
HTML;
    }
}
