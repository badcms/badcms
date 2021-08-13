<?php

define('CMS_START', microtime(true));

// Вспомогательная функция для автозагрузки функций в неймспейсы
function loadFunctions($modules)
{
    foreach ($modules as $module) {
        $ns = '\\BadCMS\\'.ucfirst($module);
        if (class_exists($class = $ns)) {
            $class::load;
        } elseif (class_exists($class = $ns.'\\Functions')) {
            $class::load;
        }
    }
}

/*
|--------------------------------------------------------------------------
| Регистрируем Автолоадер Composer
|--------------------------------------------------------------------------
*/
require __DIR__.'/../vendor/autoload.php';
