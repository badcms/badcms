<?php
/**
 * В этом скрипте указаны маршруты для обработки `BadCMS\Router`
 */

use function BadCMS\View\render;
use function BadCMS\Auth\login;
use function BadCMS\Auth\user;
use function BadCMS\Http\redirect;
use function BadCMS\Router\route;

if (!function_exists('error')) {
    // Обработка отрисовки страниц с ошибками
    function error($reason, $code)
    {
        return function () use ($reason, $code) {
            header("HTTP/1.1 $code $reason");

            return render("errors/".$code);
        };
    }
}

/**
 * Пример описания маршрута:
 *
 *       `/plugins/ext-template` - uri для маршрута и его имя
 *       Для маршрута будет задан псевдоним 'plugins.ext-template'
 *       для обращения к нему в коде `route('plugins.ext-template')`
 *
 *       Значение - анонимная функция которая вызывается для обработки действия
 *       при обращении к uri `/plugins/ext-template`.
 *
 *       Результат функции будет отрисован в теле страницы, в шаблоне `content`
 *
 *       '/plugins/ext-template' => function ($request) {
 *          return render("content", ["content" => $html]);
 *      },
 *
 * Альтернативный вариант описания маршрута:
 * [
 *      'route.name' => [
 *            'action' => function(array $request){
 *                // some code
 *            },
 *            'name' => 'route.name',
 *            'uri' => '/path/to/route',
 *      ]
 * ]
 *
 *
 * Альтернативный вариант добавления маршрута, в рантайме:
 *
 * \BadCMS\Router\addRoute('route.name', function (array $request) {
 *     //TODO: add your code
 *     return 'OK";
 * });
 *
 */

return [
    // Страница авторизации
    'auth' => function (array $request) {
        if (user()) {
            redirect(route('index'));
        }

        return render('auth', []);
    },

    // Method: POST, обработка введенных логина и пароля
    'login' => function (array $request) {
        if (login($request)) {
            // Если авторизация прошла успешно - редиректим на главную страницу
            redirect(route('index'));
        } else {
            // Сообщение пользователю
            flash("error", "Invalid username or password");
            // Переадресация на форму авторизации
            redirect(route('auth'));
        }
    },

    // Главная страница
    'index' => function () {
        return render('index');
    },

    // Ошибки
    '500' => error("Internal Server Error", 500),
    '404' => error("Not found", 404),
    '403' => error("Forbidden", 403),
    '400' => error("Validation failed", 400),

    // Разлогиниваем пользователя
    'logout' => function () {
        unset($_SESSION[SESSION_USER_KEY]);
        session_commit();
        redirect(route('index'));
    },
];
