<?php

namespace Core;

class Router
{
	public function getTrack($routes, $uri)
	{
		echo '<pre>';
//		var_dump($routes);

		foreach ($routes as $route) {
			var_dump($route->path);
			var_dump('uri= ' . $uri);

			if (str_contains(strtolower($uri), strtolower($route->path))) {
				return new Route(
					$route->path,
					$route->controller,
					$route->action
				);
			}
		}
	}
}