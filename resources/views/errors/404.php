<?php

$title = "Page not found";
include(view_path("_header.php"));
?>

    <div class="container">
        <div class="row">
            <div class="col-sm-12" style="padding: 200px">
                <h1>404 - Page not found</h1>
                <?php
                if ($message): ?>
                    <pre class="error left"><?= $message; ?></pre>
                <?php
                endif; ?>
            </div>
        </div>
    </div>

<?php
include(view_path("_footer.php"));

