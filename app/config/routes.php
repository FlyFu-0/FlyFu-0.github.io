<?php

use Core\Route;

return [
	new Route('/index/', 'message', 'index'),
	new Route('/hello/', 'message', 'hello'),
	new Route('/login/', 'auth', 'login'),
	new Route('/logout', 'auth', 'logout'),
	new Route('/register/', 'auth', 'register'),
];
