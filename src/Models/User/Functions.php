<?php

namespace BadCMS\Models\User;

use function BadCMS\Application\app;
use function BadCMS\Auth\deny;
use function BadCMS\Auth\hashPassword;
use function BadCMS\Http\redirect;
use function BadCMS\View\render;
use function BadCMS\Router\addRoutes;
use function BadCMS\Router\route;
use function BadCMS\Storage\create as createStorage;

use const BadCMS\Storage\STORAGE_TYPE_SERIALIZED;

class Functions
{
    const load = 1;
}

setRoutes();

function setRoutes()
{
    $routes = [
        'users.view' => [
            'action' => function ($request, $username) {
                if (is_array($username)) {
                    $username = $username['username'];
                }
                $user = findUser($username);

                return render('users/view', compact('user'));
            },
            'uri' => '/users/{username}',
        ],
        'users.edit' => [
            'action' => function ($request, $username) {
                !can('manage.users') && deny("Access denied");

                $update = $request['method'] == 'POST';

                if (is_array($username)) {
                    $username = $username['username'];
                }
                $user = findUser($username);

                if ($update) {
                    $formData = $request["all"]();
                    $updated = updateUser($username, $formData);

                    flash('message', "Изменения".(!$updated ? " не" : "")." сохранены");
                    if ($updated) {
                        redirect(route('users.view', ['username' => $user['username']]));
                    } else {
                        $user = $formData;
                    }
                }

                return render('users/edit', compact('user'));
            },
            'uri' => '/users/{username}/edit',
        ],
        'users.delete' => [
            'action' => function ($request, $username) {
                !can('manage.users') && deny("Access denied");

                if (is_array($username)) {
                    $username = $username['username'];
                }
                $user = findUser($username);

                if (deleteUser($user)) {
                    flash('message', 'Пользователь удален');
                }

                redirect(route('users'));
            },
            'uri' => '/users/{username}/delete',
        ],
        'users' => function () {
            return render('users/list', ['users' => getUsers()]);
        },
        'users.create' => [
            'action' => function ($request) {
                !can('manage.users') && deny("Access denied");

                $isPost = $request['method'] == 'POST';

                if ($isPost) {
                    $userData = $request["all"]();

                    $created = createUser($userData);

                    if ($created) {
                        flash('message', 'Пользователь создан');
                        redirect(route('users'));

                        return '';
                    }
                } else {
                    $userData = [];
                }

                return render('users/edit', ['isNew' => true, 'user' => $userData]);
            },
            'uri' => '/users/create',
        ],
    ];

    addRoutes($routes);
}

function storage()
{
    static $USERS_STORAGE;

    return $USERS_STORAGE ?? $USERS_STORAGE = createStorage(storage_path('db/users.db'), STORAGE_TYPE_SERIALIZED);
}

function getUsers()
{
    $users = @storage()["read"]();

    return $users ?: [];
}


function createUser($userData)
{
    $users = app('users');

    $username = $userData['username'] ?? null;

    $userData['role'] ?: $userData['role'] = 'user';

    if (!$username) {
        flash('message', 'Имя пользователя не указано');

        return false;
    }

    if (!findUser($username)) {
        $newData = $userData;
        $newData['password'] = hashPassword($userData['password']);
        $users[] = $newData;
        saveUsers($users);

        return true;
    } else {
        flash('message', 'Имя пользователя занято');
    }

    return false;
}

function deleteUser($userData)
{
    $users = app('users');

    $username = $userData['username'] ?? null;

    if (!$username) {
        flash('message', 'Имя пользователя не указано');

        return false;
    }

    for ($i = 0; $i < count($users); $i++) {
        if ($users[$i]['username'] == $username) {
            unset($users[$i]);
            saveUsers($users);
            flash('message', 'Пользователь удален');

            return true;
        }
    }


    flash('message', 'Ошибка удаления пользователя');

    return false;
}


function updateUser($username, $userData)
{
    $users = app('users');

    $isDirty = false;

    foreach ($users as &$user) {
        if ($user['username'] == $username) {
            $newData = $userData;
            if (isset($userData['password']) && $userData['password'] != '') {
                $newData['password'] = hashPassword($userData['password']);
            }
            $user = $newData;
            $isDirty = true;
            break;
        }
    }

    if ($isDirty) {
        saveUsers($users);

        return true;
    }

    return false;
}

function saveUsers($data)
{
    return storage()["write"]($data);
}

function actionLinks($user)
{
    $params = ['username' => $user["username"]];
    $actions = [
        "view" => [
            "label" => '<i class="fas fa-eye"></i>',
            'alt' => 'Просмотр',
            "url" => route('users.view', $params),
        ],
        "edit" => [
            "label" => '<i class="fas fa-edit"></i>',
            'alt' => 'Редактировать',
            "url" => route('users.edit', $params),
        ],
        "delete" => [
            "label" => '<i class="fas fa-times"></i>',
            'alt' => 'Удалить',
            "url" => route('users.delete', $params),
        ],
    ];

    if (!can('manage.users')) {
        unset($actions['edit'], $actions['delete']);
    }

    foreach ($actions as $name => $action) {
        $html .= <<<HTML
<a href="{$action['url']}" title="{$action['alt']}">{$action["label"]}</a>&nbsp;
HTML;
    }

    return $html;
}

function findUser($username)
{
    $users = app('users');

    $found = array_filter($users, function ($user) use ($username) {
        return strtolower($user["username"]) == strtolower($username);
    });

    return array_shift($found);
}
