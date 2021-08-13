<?php

namespace BadCMS\DotEnv;

class Functions
{
    const load = 1;
}

static $ENV = null;

function env($key, $default = null)
{
    global $ENV;

    if ($ENV === null) {
        $ENV = readDotEnv();
    }

    return $ENV[$key] ?? $default;
}

function readDotEnv()
{
    $environment = [];
    if (file_exists($envPath = APP_ROOT."/.env")) {
        $envData = explode("\n", str_replace("\r\n", "\n", file_get_contents($envPath)));
        $environment = array_reduce($envData, function ($data, $line) {
            if (trim($line)) {
                list($key, $value,) = explode("=", $line);
                $data[$key] = trim($value, "\r\n\t \"'");
            }

            return $data;
        }, []);
    }

    return $environment;
}
