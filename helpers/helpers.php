<?php

require_once "TableBuilder.php";

use function BadCMS\DotEnv\env as DotEnv;

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        return DotEnv($key, $default);
    }
}
