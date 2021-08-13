<?php

return [
    "permissions" => [
        "view.menu" => ['*'],
        "manage.users" => ['admin'],
    ],
    // Crypt settings
    "hash" => PASSWORD_DEFAULT, // algorythm
    "cost" => 10,               // algorithmic cost
];
