<?php

namespace Core;

abstract class DBBuilder
{
	private DB $db;

	protected function getDB(): DB
	{
		$dbObject = new DB();
		$dbObject->setTable($this->getTable());

		return $dbObject;
	}

	abstract protected function getTable(): string;
}