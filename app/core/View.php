<?php

namespace Core;

class View
{
	public function render(Page $page)
	{
		return $this->renderLayout($page, $this->renderView($page));
	}

	//TODO: bug - header tags in body tag
	private function renderLayout(Page $page, $content)
	{
		$layout = $_SERVER['DOCUMENT_ROOT']
			. "/app/layouts/{$page->layout}.php";

		if (file_exists($layout)) {
			$title = $page->title;
			$message = $page->message;
			include $layout;
		} else {
			echo "Layout file undefined: $layout";
			die();
		}
	}

	private function renderView(Page $page)
	{
		if ($page->view) {
			$view = $_SERVER['DOCUMENT_ROOT']
				. "/app/views/{$page->view}.php";
			if (file_exists($view)) {
				$data = $page->data;
				extract($data);
				include $view;
			} else {
				echo "View file undefined: $view";
				die();
			}
		}
	}
}