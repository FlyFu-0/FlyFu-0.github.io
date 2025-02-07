<?php

namespace Core;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config.php';

use Registry;

use PDO;

session_start();

class DB
{
	private static PDO $db;

	private function __construct()
	{
	}

	public static function getInstance(): PDO
	{
		if (!isset(static::$db)) {
			$dsn = 'mysql:dbname=' . DBNAME . ';host=' . HOST;
			static::$db = new PDO($dsn, USER, PASSWORD);
			static::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		return static::$db;
	}
}
