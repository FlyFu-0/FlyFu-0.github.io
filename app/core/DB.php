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
		if (!isset(self::$db)) {
			$dsn = 'mysql:dbname=' . DBNAME . ';host=' . HOST;
			self::$db = new PDO($dsn, USER, PASSWORD);
			self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		return self::$db;
	}
}
