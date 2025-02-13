<?php

namespace Core;

class View
{
	public function render(Page $page)
	{
		return $this->renderLayout($page, $this->renderView($page));
	}

	private function renderLayout(Page $page, $content)
	{
		$layout = $_SERVER['DOCUMENT_ROOT']
			. "/app/layouts/{$page->layout}.php";

		if (file_exists($layout)) {
			$title = $page->title;
			$message = $page->message;
			$assets = $page->assets;


			$connections = $this->connectionConstructor($assets);

			include $layout;
		} else {
			echo "Layout file undefined: $layout";
			die();
		}
	}

	public function connectionConstructor(mixed $assets): array
	{
		$connections = [];
		foreach ($assets as $asset) {
			$inner = implode(
				' ',
				array_map(fn($key, $value) => "{$key}=\"{$value}\"",
					array_keys($asset['attributes']),
					array_values($asset['attributes']))
			);
			$connections[] = ($asset['selfClosing'])
				? "<{$asset['type']} {$inner} />"
				: "<{$asset['type']} {$inner} ></{$asset['type']}>";
		}
		return $connections;
	}

	private function renderView(Page $page)
	{
		if ($page->view) {
			$view = $_SERVER['DOCUMENT_ROOT']
				. "/app/views/{$page->view}.php";
			if (file_exists($view)) {
				ob_start();
				$data = $page->data;
				extract($data);
				include $view;
				return ob_get_clean();
			} else {
				echo "View file undefined: $view";
				die();
			}
		}
	}
}