<?php

namespace Core;

class Route
{
	private $path;
	private $controller;
	private $action;

	public function __construct(string $path, string $controller, string $action)
	{
		$this->path = $path;
		$this->controller = $controller;
		$this->action = $action;
	}

	public function __get(string $name)
	{
		return $this->$name;
	}
}