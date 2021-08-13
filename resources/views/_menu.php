<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <header class="sticky">
                <a href="/" class="logo">BadCMS</a>
                <a href="/" class="button">Home</a>
                <?php
                if (!\BadCMS\Auth\guest()):
                    $loggedUser = \BadCMS\Auth\user();
                    ?>
                    <a href="/users" class="button">Users</a>
                    <a href="/logout" class="button"> <i><?= $loggedUser['username']; ?></i> [ Logout ]</a>
                <?php
                endif; ?>
            </header>
            <?php
            $menu = \BadCMS\Application\app("menu");
            if ($menu && count($menu)): ?>
                <header class="small">
                    <a>Plugins:</a>
                    <?php
                    foreach ($menu as $menuItem) {
                        echo '<a href="'.$menuItem['action'].'" class="button">'.$menuItem["label"].'</a>';
                    }
                    ?>
                </header>
            <?
            endif; ?>
        </div>
    </div>
</div>
