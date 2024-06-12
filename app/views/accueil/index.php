<?php
require_once dirname(__DIR__) . DS . 'templates' . DS . 'header.php';
require_once dirname(__DIR__, 2) . DS . 'controllers' . DS . 'accueilController.php';
?>

<div class="container">


    <main>
        <?php
        if (isset($args['posts'])) {
            $posts = $args['posts'];
            $posts = array_reverse($posts); // Inverser l'ordre des posts
            for ($i = 0; $i < count($posts); $i++) {
                echo "<div class='form-add-post'>";
                echo "<h3>" . $posts[$i]['uti_pseudo'] . "</h3><br>";
                echo "<h3>" . $posts[$i]['pos_title'] . "</h3><br>";
                echo "<p>" . $posts[$i]['pos_content'] . "</p><br>";
                echo "</div>";
            }
        } else {
            echo "Aucun utilisateur trouvé.";
        }
        ?>
    </main>
</div>

<?php require_once dirname(__DIR__) . DS . 'templates' . DS . 'footer.php'; ?>