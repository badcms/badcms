<?php

require 'constants.php';

// Сообщения об ошибках
error_reporting(~E_NOTICE);

// Инициализируем зависимости
[
    \BadCMS\DotEnv\Functions::load,
    \BadCMS\PluginManager\Functions::load,
    \BadCMS\Application\Functions::load,
];

return \BadCMS\Application\app();
