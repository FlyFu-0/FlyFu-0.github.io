<?php

spl_autoload_register(function ($class) {
	$prefix = 'App\\';
	$base_dir = $_SERVER['DOCUMENT_ROOT'] . '/app/';

	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) {
		return;
	}

	$relative_class = substr($class, $len);

	$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

//	var_dump($file);

	if (file_exists($file)) {
		require $file;
	}
});
