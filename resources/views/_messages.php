<?php

if ($message = flash()) {
    ?>
    <pre><?= $message; ?></pre>
    <?php
}

if ($message = flash("error")) {
    ?>
    <pre><?= $message; ?></pre>
    <?php
}

