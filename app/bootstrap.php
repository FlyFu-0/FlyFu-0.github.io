<?php

namespace App;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/autoloader.php';

use Controllers;
use Core;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/helpers/flash.php';

$routes = require $_SERVER['DOCUMENT_ROOT'] . '/app/config/routes.php';

$route = (new Core\Router)->getTrack($routes, $_SERVER['REQUEST_URI']);

$page = (new Core\Dispatcher)->getPage($route);

(new Core\View)->render($page);

//$url = $_GET['url'] ?? '';
//
//switch ($url) {
//	case 'register':
//		$controller = new Controllers\Auth();
//		$controller->register();
//		break;
//	case 'login':
//		$controller = new Controllers\Auth();
//		$controller->login();
//		break;
//	case 'logout':
//		$controller = new Controllers\Auth();
//		$controller->logout();
//		break;
//	default:
//		$controller = new Controllers\Message();
//		$controller->index();
//		break;
//}
