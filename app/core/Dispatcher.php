<?php

namespace Core;

class Dispatcher
{
	public function getPage(Route $route): Page
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
			echo "<script>console.log($e))</script>";
			return new Page('default', 'Dispatcher Error', 'error/notFound');
		}
	}
}