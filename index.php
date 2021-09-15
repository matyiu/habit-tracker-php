<?php

require './vendor/autoload.php';
require './utils/view.php';
require './vars/config.php';

use App\Controllers\HabitController;
use App\Router;

$router = new Router;

$router->get('', [HabitController::class, 'index']);
$router->post('', [HabitController::class, 'store']);

$router->run();