<?php

$title = "Пользователь";
include(view_path("_header.php"));

/**
 * @var array $user
 */
?>
    <div class="container">
        <table class="horizontal">
            <caption>Пользователь <?= $user["username"]; ?></caption>
            <thead>
            <tr>
                <th>Username</th>
                <th>E-mail</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>BIO</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td data-label="Username"><?= $user['username']; ?></td>
                <td data-label="E-mail"><?= $user['email'] ?? "&nbsp;"; ?></td>
                <td data-label="Firstname"><?= $user['firstname'] ?? "&nbsp;"; ?></td>
                <td data-label="Lastname"><?= $user['lastname'] ?? "&nbsp;"; ?></td>
                <td data-label="BIO"><?= $user['bio'] ?? "&nbsp;"; ?></td>
            </tr>
            </tbody>
        </table>
        <?php
        if (can('manage.users')):
            ?>
            <a href="<?= \BadCMS\Router\route("users.edit", ['username' => $user['username']]); ?>" role="button"><i
                        class="fas fa-edit"></i> Редактировать</a>
        <?php
        endif; ?>
    </div>
<?php
include(view_path("_footer.php"));
