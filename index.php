<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/MessageController.php';

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

var_dump($url);

switch ($url) {
    case '/':
    case '/messages':
        $controller = new MessageController();
        $controller->index();
        break;
        // case '/messages/create':
        //     $controller = new MessageController();
            // $controller->create();
            // break;
    default:
        $controller = new MessageController();
        $controller->index();
        break;
}
