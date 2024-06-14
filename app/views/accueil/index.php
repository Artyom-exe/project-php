<?php
require_once dirname(__DIR__) . DS . 'templates' . DS . 'header.php';
require_once dirname(__DIR__, 2) . DS . 'controllers' . DS . 'accueilController.php';
?>

<div class="container">
    <main>
        <p>Bienvenue sur <strong>Project-PHP</strong>, votre nouvelle plateforme dédiée aux passionnés de développement web et PHP! Rejoignez notre communauté dynamique en <strong>créant un compte dès aujourd'hui</strong>. Partagez vos idées, projets, et astuces en <strong>postant directement dans le flux</strong>. Ensemble, faisons grandir nos compétences et notre réseau. <strong>Inscrivez-vous maintenant</strong> et commencez à contribuer!</p>
        <?php
        if (isset($args['posts'])) {
            $posts = $args['posts'];
            $posts = array_reverse($posts); // Inverser l'ordre des posts
            for ($i = 0; $i < count($posts); $i++) {

                // Décoder les entités HTML pour chaque champ de contenu
                $posts[$i]['pos_title'] = html_entity_decode($posts[$i]['pos_title']);
                $posts[$i]['pos_content'] = html_entity_decode($posts[$i]['pos_content']);

                echo "<div class='post-card'>";
                echo "<div class='post-header'>";
                echo "<h3>" . htmlspecialchars($posts[$i]['uti_pseudo']) . "</h3>";
                echo "</div>";
                echo "<div class='post-body'>";
                echo "<h3>" . htmlspecialchars($posts[$i]['pos_title']) . "</h3>";
                echo "<p>" . nl2br(htmlspecialchars($posts[$i]['pos_content'])) . "</p>";
                echo "</div>";
                echo "<div class='post-footer'>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "Aucun utilisateur trouvé.";
        }
        ?>

    </main>
</div>

<?php require_once dirname(__DIR__) . DS . 'templates' . DS . 'footer.php'; ?>