<?php

$title = "Внутренняя ошибка сервера";
include(view_path("_header.php"));
?>

    <div class="container">
        <div class="row">
            <div class="col-sm-12" style="padding: 200px">
                <h1><?=$title;?></h1>
                <pre class="error left"><?=$message;?>

<?=$trace;?>
                </pre>
            </div>
        </div>
    </div>

<?php
include(view_path("_footer.php"));

