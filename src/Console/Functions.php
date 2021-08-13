<?php

namespace BadCMS\Console;

class Functions
{
    const load = 1;
    static $commands;
}


function init()
{
    Functions::$commands = [
        'users:list' => function ($argv) {
            //вывести список пользователей в таблице
            $users = \BadCMS\Models\User\getUsers();
            $header = null;

            $headers = ["username", "firstname", "lastname", "email", "role"];
            foreach ($users as $key => &$fields) {
                $fields = array_intersect_key($fields, array_flip($headers));
            }

            $tableBuilder = new \TableBuilder();
            $table = $tableBuilder->getTableRows($users, $headers);
            $tableBuilder->echoTableRows($table);
        },
        'key:generate' => function ($argv) {
            if (file_exists($envPath = app_path('.env'))) {
                $content = file_get_contents($envPath);
                $secret = sha1(random_bytes(32));
                $appSecret = "APP_KEY=$secret";
                if (preg_match('/APP_KEY=([^\r\n]*)/i', $content, $match)) {
                    if ($match[1] != "") {
                        echo "\033[033m"."Key already generated. Skipping"."\033[0m".PHP_EOL;

                        return;
                    }

                    $content = str_replace($match[0], $appSecret, $content);
                } else {
                    $content .= PHP_EOL.$appSecret.PHP_EOL;
                }

                file_put_contents($envPath, $content);
                echo "\033[032m"."Key successfully generated"."\033[0m".PHP_EOL;
            } else {
                echo "\033[031m"."File .env not found in application root directory. Skipping"."\033[0m".PHP_EOL;
            }
        },
    ];
}

init();

function argvInput()
{
    array_shift($_SERVER['argv']);

    return $_SERVER['argv'];
}

function handleCLI($argv)
{
    $command = $argv[0] ?? null;

    if ($command && isset(Functions::$commands[$command])) {
        $command = Functions::$commands[$command];
        array_shift($argv);

        $command($argv);
    } else {
        if ($command) {
            echo "\033[31mCommand `$command` not found \033[0m";
        }

        $help = [
            "\nBadCMS",
            "\033[33m"."Usage:",
            "\033[0m\t"."command [arguments]",
            "",
            "\033[32mAvailable commands:\n",
        ];

        echo implode(PHP_EOL, $help);

        foreach (Functions::$commands as $command => $func) {
            echo "\t$command\n";
        }
    }
    echo "\033[0m"; // reset terminal colors
}
