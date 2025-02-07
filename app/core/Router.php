<?php

namespace Core;

class Router
{
	public function getTrack($routes, $uri)
	{

		foreach ($routes as $route) {

			if ($this->findMathRoute($uri, $route->path)) {

				return new Route(
					$route->path,
					$route->controller,
					$route->action
				);
			}
		}

		return new Route('error/', 'error', 'notFound');
	}

	private function findMathRoute($uri, string $route): bool
	{
		return str_contains(strtolower($uri), strtolower($route));
	}
}