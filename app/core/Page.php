<?php

namespace Core;

class Page
{
	private $layout;
	private $title;
	private $message;
	private $view;
	private $data;

	public function __construct($layout, $title = '', $view = null, $data = [], $message = '')
	{
		$this->layout = $layout;
		$this->title = $title;
		$this->view = $view;
		$this->data = $data;
		$this->message = $message;
	}

	public function __get(string $name)
	{
		return $this->$name;
	}
}