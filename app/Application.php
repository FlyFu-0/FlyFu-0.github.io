<?php

namespace app;

use Core;

spl_autoload_register([Application::class, 'autoloader']);

class Application
{
	public static function run(): void
	{
		try {
			session_start();

			$routes = require $_SERVER['DOCUMENT_ROOT']
				. '/app/config/routes.php';

			$page = (new Core\Router)->getTrack(
				$routes,
				$_SERVER['REQUEST_URI']
			);

			(new Core\View)->render($page);
		} catch (\Exception $e) {
			(new Core\View)->render(
				new Core\Page('default', 'Error', 'error/error', [
					'code' => $e->getCode(),
					'message' => $e->getMessage(),
				])
			);
		}
	}

	public static function autoloader($class): void
	{
		$file = str_replace('\\', '/', __DIR__ . '/' . $class) . '.php';

		if (file_exists($file)) {
			require $file;
		}
	}
}