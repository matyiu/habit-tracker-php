<?php

require './vendor/autoload.php';
require './utils/view.php';

use App\Router;
use App\Habit;

$router = new Router;

$router->get('', function() {
    $habits = [
        new Habit('test', 50),
        new Habit('test2', 21),
        new Habit('test3', 21),
        new Habit('test4', 50),
    ];

    return view('index.view.php', ['habits' => $habits]);
});

$router->run();