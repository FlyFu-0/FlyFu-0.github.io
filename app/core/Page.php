<?php

namespace Core;

class Page
{
	private $layout;
	private $title;
	private $message;
	private $view;
	private $data;
	private $assets;

	public function __construct($layout, $title = '', $view = null, $data = [], $message = '', $assets = [])
	{
		$this->layout = $layout;
		$this->title = $title;
		$this->view = $view;
		$this->data = $data;
		$this->message = $message;
		$this->assets = $assets;
	}

	public function __get(string $name)
	{
		return $this->$name;
	}
}