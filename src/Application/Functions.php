<?php

namespace BadCMS\Application;

use function BadCMS\Auth\check;
use function BadCMS\DotEnv\readDotEnv;
use function BadCMS\Http\redirect;
use function BadCMS\PluginManager\loadPlugins;
use function BadCMS\Router\addRoutes;
use function BadCMS\Router\route;
use function BadCMS\Storage\eachEntry;
use function BadCMS\Storage\readJSON;
use function BadCMS\Application\readConfig as getAppConfig;

use function BadCMS\Router\loadRoutes as getAppRoutes;
use function BadCMS\Models\User\getUsers as getAppUsers;

use function BadCMS\Http\request;
use function BadCMS\Http\response;
use function BadCMS\Storage\readPHP;
use function BadCMS\View\render;

//Dirty hack for autoload
class Functions
{
    const load = 1;
    static $APPLICATION;
}

[
    \BadCMS\View\Functions::load,
    \BadCMS\Storage\Functions::load,
    \BadCMS\Router\Functions::load,
    \BadCMS\Auth\Functions::load,
];

/**
 * BadCMS Application instance
 */
function app($section = null, $data = null)
{
    if (!isset(Functions::$APPLICATION)) {
        $request = [];

        Functions::$APPLICATION = [
            "init" => $init = function () {
                getAppRoutes();
                Functions::$APPLICATION['users'] = getAppUsers();
                Functions::$APPLICATION['plugins'] = loadPlugins();
            },
            "config" => getAppConfig(),

            'request' => function ($_request = null) use (&$request) {
                if ($_request && is_array($_request)) {
                    $request = array_merge($request, $_request);
                } elseif (is_string($_request)) {
                    return $request[$_request] ?? null;
                }

                return $request;
            },

            'router' => [
                "addRoute" => function ($route) {
                    if (!isset(Functions::$APPLICATION['routes'])) {
                        Functions::$APPLICATION['routes'] = [];
                    }
                    Functions::$APPLICATION['routes'][$route["name"]] = $route;
                },
            ],

            "addMenu" => function ($menu = null) {
                if (!isset(Functions::$APPLICATION['menu'])) {
                    Functions::$APPLICATION['menu'] = [];
                }

                foreach ($menu as $menuItems) {
                    $menuItem = is_callable($menuItems) ? $menuItems() : $menuItems;
                    if (isset($menuItem['label'])) {
                        $menuItem = [$menuItem];
                    }
                    foreach ($menuItem as $item) {
                        Functions::$APPLICATION['menu'][] = $item;
                    }
                }

                return $menu;
            },

            "handle" => function () {
                try {
                    ob_start();

                    // Get request data
                    $request = request();

                    session_start();

                    // Process request
                    $data = handleRequest($request);

                    // Save session
                    session_commit();

                    // Process response
                    $response = response($data);
                    if (is_array($response)) {
                        if ($response['headers']) {
                            array_map("header", \BadCMS\Http\headers($response['headers']));
                        }
                        $content = $response['content'];
                    } else {
                        $content = $response;
                    }
                    echo $content;
                    echo ob_get_clean();
                }
                catch (\Exception $exception) {
                    $code = $exception->getCode();
                    \BadCMS\Logger\log($exception->getMessage()." [$code]", ["code" => $code, "trace" => $exception->getTrace()]);
                    ob_clean();
                    switch (true) {
                        case $code == 404:
                            //flash('error', $exception->getMessage());
                            redirect(route('404'), ['message' => $exception->getMessage()]);
                            break;
                        case $code == 403:
                            redirect(route('auth'));
                            break;
                        default:
                            $code = 500;
                            echo render("errors/".$code,
                                ["message" => $exception->getMessage(), "trace" => $exception->getTraceAsString()]);
                            break;
                    }
                }
            },
        ];

        $init();
    }

    if ($data) {
        Functions::$APPLICATION[$section] = $data;
    };

    return $section ? Functions::$APPLICATION[$section] : Functions::$APPLICATION;
}

function readConfig()
{
    $config = [];
    $appKey = env('APP_KEY');
    if (!defined("BADCMS_CLI") && !$appKey) {
        die("Invalid configuration. Application `APP_KEY` is not set in environment settings");
    }

    array_dot_set($config, "app.key", $appKey);


    eachEntry(CONFIG_ROOT, function ($file) use (&$config) {
        $fileInfo = pathinfo($file);
        $ext = strtolower($fileInfo['extension']);

        if( !isset($config[$fileInfo['filename']])){
            $config[$fileInfo['filename']] = [];
        }

        if ($ext === 'php') {
            $config[$fileInfo['filename']] = array_merge_recursive($config[$fileInfo['filename']], readPHP($file) ?? []);
        }
    });

    return $config;
}

function handleRequest($request)
{
    // Check session auth
    $user = check();
    $request["user"] = $user;

    $authExceptRoutes = [
        '/auth',
        '/login',
        '/404',
        '/500',
    ];

    // check route
    if (is_null($request["route"])) {
        flash("error", "Page not found: /".implode("/", $request["uri"]));

        return function () { redirect(route('404'), 302); };
    }

    if (!$user && !in_array($request["route"]['name'], $authExceptRoutes)) {
        return function () { redirect(route('auth'), 302); };
    }

    $action = $request["route"]["action"];

    return is_callable($action) ? runAction($action, [$request]) : $action;
}


