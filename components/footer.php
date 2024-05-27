<footer>

    <p>© 2024 | Copyright | Project-PHP, All rights reserved</p>

    <!-- Liste des liens de navigation pour le pied de page -->
    <ul>
        <!-- Utilisation de la fonction nav_item pour générer les liens de navigation -->
        <?= nav_item('index.php', 'Accueil'); ?>
        <?= nav_item('contact.php', 'Contact'); ?>
        <?= nav_item('connection.php', 'Connexion'); ?>
        <?= nav_item('profil.php', 'Mon profil'); ?>
    </ul>
</footer>
</body>

</html>