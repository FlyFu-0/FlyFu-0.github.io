<?php

namespace App;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/autoloader.php';

use Core;

Core\DB::getInstance();

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/helpers/flash.php';

$routes = require $_SERVER['DOCUMENT_ROOT'] . '/app/config/routes.php';

$route = (new Core\Router)->getTrack($routes, $_SERVER['REQUEST_URI']);

$page = (new Core\Dispatcher)->getPage($route);

(new Core\View)->render($page);
