<footer>

    <p>© 2024 | Copyright | Project-PHP, All rights reserved</p>

    <!-- Liste des liens de navigation pour le pied de page -->
    <ul>
        <!-- Utilisation de la fonction nav_item pour générer les liens de navigation -->
        <?= nav_item('/', 'Accueil'); ?>
        <?= nav_item('/contact', 'Contact'); ?>
        <?= nav_item('/connexion', 'Connexion'); ?>
        <?= nav_item('/profil', 'Mon profil'); ?>
    </ul>
</footer>
</body>

</html>