<?php

namespace Core;

class Dispatcher
{
	public function getPage(Route $route)
	{
		$className = ucfirst($route->controller);
		$fullName = "Controllers\\$className";

		try {
			$controller = new $fullName;
			if (method_exists($controller, $route->action)) {
				$result = $controller->{$route->action}();

				if ($result) {
					return $result;
				} else {
					return new Page('default');
				}
			}
		} catch (\Exception $e) {
			echo $error->getMessage();
			die();
		}
	}
}