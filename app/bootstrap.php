<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/autoloader.php';

use App\Models\Message as MessageModel;
use App\Models\Auth as AuthModel;

use App\Controllers\Auth as AuthController;
use App\Controllers\Message as MessageController;

use App\Core\DB;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/helpers/flash.php';

$url = $_GET['url'] ?? '';

switch ($url) {
	case 'register':
		$controller = new AuthController();
		$controller->register();
		break;
	case 'login':
		$controller = new AuthController();
		$controller->login();
		break;
	case 'logout':
		$controller = new AuthController();
		$controller->logout();
		break;
	default:
		$controller = new MessageController();
		$controller->index();
		break;
}
