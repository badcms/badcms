<?php

$title = "Пользователи";
include(view_path("_header.php"));

/**
 * @var array $users
 */

use function BadCMS\Router\route;
?>
    <div class="container">

        <table class="striped">
            <caption>Пользователи
                <?php if (can('user.manage')): ?>
                    <a class="button small" style="float: right" href="<?= route('users.create'); ?>"><i
                                class="fas fa-plus-square"></i> Новый пользователь</a>
                <?php endif; ?>
            </caption>
            <thead>
            <tr>
                <th>№</th>
                <th>Имя пользователя</th>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Роль</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($users as $idx => $user): ?>
                <tr>
                    <td>#<?= ++$idx; ?></td>
                    <td data-label="Имя пользователя"><?= $user["username"]; ?></td>
                    <td data-label="Фамилия"><?= $user["lastname"]; ?></td>
                    <td data-label="Имя"><?= $user["firstname"]; ?></td>
                    <td data-label="Роль"><?= $user["role"]; ?></td>
                    <td>
                        <?= \BadCMS\Models\User\actionLinks($user); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php include(view_path("_footer.php"));
