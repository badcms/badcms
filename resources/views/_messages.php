<?php

if ($message = flash()) {
    ?>
    <pre><?= $message; ?></pre>
    <?php
}

$message = flash("error");
