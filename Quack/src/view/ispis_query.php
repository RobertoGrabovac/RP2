<?php require_once __DIR__ . '/_header_nav.php'; 
require_once __DIR__ . '/../model/quack.class.php';
?>

<p>
    <ul class="quack-list">
        <?php
           while ($row = $st->fetch()) {
                $qk = new Quack();
                if (isset($row['quack'])) $better_query = $qk->interactive_quack($row['quack']);
                echo '<li>';
                echo '<div class="container">';
                if (isset($row['username'])) echo '<div class="username"> @' . $row['username'] . '</div>';
                if (isset($row['date'])) echo '<div class="date"> ' . $row['date'] . '</div>';
                if (isset($row['quack'])) echo '<div class="post"> ' . $better_query . '</div>';
                echo '</div>';
                echo '</li>';
            }
        ?>
    </ul>
</p>

<?php require_once __DIR__ . '/_footer.php'; ?>