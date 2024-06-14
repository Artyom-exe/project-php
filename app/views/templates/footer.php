<footer>

    <p>© 2024 | Copyright | Project-PHP, All rights reserved</p>

    <!-- Liste des liens de navigation pour le pied de page -->
    <ul>
        <!-- Utilisation de la fonction nav_item pour générer les liens de navigation -->
        <?php if (!isset($_SESSION['utilisateur'])) {
            echo "<ul>";
            echo nav_item('/', 'Home');
            echo nav_item('/contact', 'Contact');
            echo nav_item('/connexion', 'Connexion');
            echo nav_item('/inscription', 'S\'enregistrer');
            echo "</ul>";
        } else {
            echo "<ul>";
            echo nav_item('/', 'Home');
            echo nav_item('/contact', 'Contact');
            echo nav_item('/profil', 'Mon profil');
            echo nav_item('/logout', 'Déconnexion');
            echo "</ul>";
        }
        ?>
    </ul>
</footer>
</body>

</html>