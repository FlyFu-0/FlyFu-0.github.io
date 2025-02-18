<?php

namespace Core;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/config.php';

use PDO;

class DBConnector
{
	protected static PDO $db;

	protected function __construct()
	{
	}

	public static function getInstance(): PDO
	{
		if (!isset(static::$db)) {
			$dsn = 'mysql:dbname=' . DBNAME . ';host=' . HOST;
			static::$db = new PDO($dsn, USER, PASSWORD);
			static::$db->setAttribute(
				PDO::ATTR_ERRMODE,
				PDO::ERRMODE_EXCEPTION
			);
		}

		return static::$db;
	}
}
