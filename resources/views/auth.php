<?php

include("_header.php");
?>

<div class="container">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-md-4 col-sm-12" style="height: 100vh; padding: 200px 0px">
            <div style="max-width: 400px; width: 100%; margin: auto">
                <?php include("_messages.php"); ?>
                <form action="<?= \BadCMS\Router\route("login"); ?>" method="post">
                    <fieldset>
                        <legend>BadCMS Control panel</legend>
                        <label for="username">Username</label>
                        <input type="text" id="Username" name="username" placeholder="Username"/>
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password"  placeholder="Password"/>
                        <input type="submit" value="login">
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="col-sm-4"></div>
    </div>
</div>

<?php
include("_footer.php");

