<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/helpers/flash.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/MessageController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/AuthController.php';

$url = $_GET['url'] ?? '';

switch ($url) {
    case '':
    case 'messages':
        $controller = new MessageController();
        $controller->index();
        break;
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
