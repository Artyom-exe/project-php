<?php
require_once dirname(__DIR__) . DS . 'templates' . DS . 'header.php';
require_once dirname(__DIR__, 2) . DS . 'controllers' . DS . 'accueilController.php';
$posts = $_SESSION['posts'];
?>

<div class="container">

    <?php if (!empty($args['errors']['maj-failed-email'])) : ?>
        <div class="error-message">
            <?= htmlspecialchars($args['errors']['maj-failed-email'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])) : ?>
        <div class="success-message">
            <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <main>
        <?php
        if (isset($_SESSION['posts'])) {
            $posts = array_reverse($posts); // Inverser l'ordre des posts
            for ($i = 0; $i < count($posts); $i++) {
                echo "<div class='form-add-post'>";
                echo "<h3>" . $posts[$i]['uti_pseudo'] . "</h3><br>";
                echo "<h3>" . $posts[$i]['pos_title'] . "</h3><br>";
                echo "<p>" . $posts[$i]['pos_content'] . "</p><br>";
                echo '<button class="btn-primary edit-btn">Modifier</button>';
                echo '<button class="btn-primary delete-btn">Supprimer</button>';
                echo "</div>";
            }
        } else {
            echo "Aucun utilisateur trouvé.";
        }
        ?>
    </main>
</div>

<?php require_once dirname(__DIR__) . DS . 'templates' . DS . 'footer.php'; ?>