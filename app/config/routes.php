<?php

use Core\Route;

return [
	new Route('/logout/', 'auth', 'logout'),
	new Route('/register/', 'auth', 'register'),
	new Route('/login/', 'auth', 'login'),

	new Route('/error/', 'error', 'notFound'),

	new Route('/', 'message', 'index'),
];
