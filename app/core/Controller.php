<?php

namespace Core;

use Core\Page;

abstract class Controller
{
	protected $layout = 'default';
	protected $title;
	protected $error;
	protected function render($view, $data = [], $assets = []): Page
	{
		return new Page($this->layout, $this->title, $view, $data, $this->error, $assets);
	}
}