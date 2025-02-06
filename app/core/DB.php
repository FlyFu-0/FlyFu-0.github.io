<?php

namespace App\Core;

use App\Config;

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
			$dsn = 'mysql:dbname=' . Config::DBNAME . ';host=' . Config::HOST;
			self::$db = new PDO($dsn, Config::USER, Config::PASSWORD);
			self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		return self::$db;
	}
}
