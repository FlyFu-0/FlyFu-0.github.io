<?php

namespace Core;

class Router
{
	public function getTrack($routes, $uri)
	{

		foreach ($routes as $route) {

			if ($this->findMathRoute($uri, $route->path)) {

				return $this->getPage($route);
			}
		}

		return new Route('error/', 'error', 'notFound');
	}

	protected function getPage(Route $route)
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

	private function findMathRoute($uri, string $route): bool
	{
		return str_contains(strtolower($uri), strtolower($route));
	}
}