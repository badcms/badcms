<?php

$title = $title ?? "Редактирование профиля";
include(view_path("_header.php"));

use function BadCMS\View\formField;
use function BadCMS\Router\route;

/**
 * @var array   $user
 * @var boolean $isNew Флаг определяющий режим редактирования - создание / редактирование
 */
?>
    <div class="container">
        <div class="section"><h3><?= ($isNew ? "Новый пользователь" : "Редактирование"); ?></h3>
            <form action="<?= route($isNew ? 'users.create' : 'users.edit', $isNew ? [] : ['username' => $user['username']]); ?>"
                  method="post">
                <div class="row responsive-label">
                    <div class="col-sm-12 col-md-3">
                        <label for="username" class="doc">Имя пользователя</label>
                    </div>
                    <div class="col-sm-12 col-md">
                        <?= formField("text", "username", "Имя пользователя", $user["username"], ['readonly' => !$isNew]); ?>
                    </div>
                </div>
                <?php
                if ($isNew):
                    ?>
                    <div class="row responsive-label">
                        <div class="col-sm-12 col-md-3">
                            <label for="password" class="doc">Пароль</label>
                        </div>
                        <div class="col-sm-12 col-md">
                            <?= formField("password", "password", "Пароль", $user["password"], ['required' => true]); ?>
                        </div>
                    </div>
                <?php
                endif;
                ?>
                <div class="row responsive-label">
                    <div class="col-sm-12 col-md-3">
                        <label for="lastname" class="doc">Фамилия</label>
                    </div>
                    <div class="col-sm-12 col-md">
                        <?= formField("text", "lastname", "Фамилия", $user["lastname"], ['required' => true]); ?>
                    </div>
                </div>
                <div class="row responsive-label">
                    <div class="col-sm-12 col-md-3">
                        <label for="firstname" class="doc">Имя</label>
                    </div>
                    <div class="col-sm-12 col-md">
                        <?= formField("text", "firstname", "Имя", $user["firstname"], ['required' => true]); ?>
                    </div>
                </div>
                <div class="row responsive-label">
                    <div class="col-sm-12 col-md-3">
                        <label for="surname" class="doc">Отчество</label>
                    </div>
                    <div class="col-sm-12 col-md">
                        <?= formField("text", "surname", "Отчество", $user["surname"], ['required' => true]); ?>
                    </div>
                </div>
                <div class="row responsive-label">
                    <div class="col-sm-12 col-md-3">
                        <label for="email" class="doc">E-mail</label>
                    </div>
                    <div class="col-sm-12 col-md">
                        <?= formField("text", "email", "E-mail", $user["email"], ['required' => true]); ?>
                    </div>
                </div>

                <div class="row responsive-label">
                    <div class="col-sm-12 col-md-3">
                        <label for="role" class="doc">Роль</label>
                    </div>
                    <div class="col-sm-12 col-md">
                        <?= formField("select", "role", "Роль", $user["role"], [
                            'items' => [
                                "user" => "user",
                                "admin" => "admin",
                            ],
                            'required' => "true",
                        ]); ?>
                    </div>
                </div>
                <div class="row responsive-label">
                    <div class="col-sm-12 col-md-3"><label for="bio" class="doc">О себе</label></div>
                    <div class="col-sm-12 col-md">
                        <?= formField("textarea", "bio", "О себе", $user["bio"]); ?>
                    </div>
                </div>
                <hr>
                <div class="row responsive-label">
                    <div class="col-sm-12 col-md-3"><label for="sf1-check" class="doc">Заблокирован ?</label></div>
                    <div class="col-sm-12 col-md">
                        <?= formField("checkbox", "blocked", "Заблокирован", $user["is_blocked"],
                            ['required' => true, "autocomplete" => "off"]); ?>
                    </div>
                </div>
                <?php
                if (!$isNew):
                    ?>
                    <div class="row responsive-label">
                        <div class="col-sm-12 col-md-3">
                            <label for="username" class="doc">Новый пароль<br><small>оставьте пустым если не хотите менять</small></label>
                        </div>
                        <div class="col-sm-12 col-md">
                            <?= formField("password", "password", "Новый пароль"); ?>
                        </div>
                    </div>
                <?php
                endif;
                ?>
                <hr>
                <div class="row">
                    <div class="col-sm-offset-6 col-sm-6 col-md-offset-8 col-md-4">
                        <div class="button-group">
                            <input type="submit" class="tertiary" value="Сохранить">
                            <input type="button" onclick="history.back()" class="secondary small" value="Отмена">
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
<?php
include(view_path("_footer.php"));
