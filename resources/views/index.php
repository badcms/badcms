<?php

$title = "Главная страница";
include("_header.php");
?>
    <div class="container">
        <?= $content ?? ""; ?>
    </div>
<?php
include("_footer.php");

