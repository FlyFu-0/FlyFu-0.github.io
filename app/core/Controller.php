<?php

namespace Core;

use Core\Page;

abstract class Controller
{
	protected string $layout = 'default';
	protected string $title = '';
	protected string $error = '';
	protected function render($view, $data = [], $assets = []): Page
	{
		return new Page($this->layout, $this->title, $view, $data, $this->error, $assets);
	}
}