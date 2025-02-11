<?php

namespace Core;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/config.php';

use PDO;

class DBConnector
{
	protected static PDO $db;

	protected function __construct() {}

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

	public function get(
		string $table,
		array $columns = ['*'],
		string $sortingField = null,
		string $order = 'ASC',
		array $joins = [],
		int $startRecord = 0,
		int $countPerPage = null,
		array $where = [],
	): array {
		$columns = implode(', ', $columns);

		$joinExpression = $this->joinExpressionBuilder($joins);

		$whereExpression = '';
		if (!empty($where)) {
			$whereExpression = $this->whereExpressionBuilder($where);
		}

		$sort = isset($sortingField) ? "ORDER BY `$sortingField` $order" : '';
		$limit = isset($countPerPage) ? "LIMIT $startRecord, $countPerPage"
			: '';

		$query = "
            SELECT $columns
            FROM `$table`
            $joinExpression
            $whereExpression
            $sort
            $limit";

		$stmt = static::$db->prepare($query);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	private function joinExpressionBuilder(array $joins): string
	{
		$joinExpression = '';
		foreach ($joins as $joinTable => $onCondition) {
			$joinExpression .= " JOIN $joinTable ON $onCondition";
		}
		return $joinExpression;
	}

	private function whereExpressionBuilder(array $where): string
	{
		$conditions = [];

		foreach ($where as $index => $condition) {
			$logic = strtoupper($condition['logic'] ?? 'AND');
			$field = $condition['field'];
			$operator = $condition['operator'] ?? '=';
			$value = $condition['value'];

			if (strtoupper($operator) === 'IN' && is_array($value)) {
				$value = "('" . implode("', '", $value) . "')";
			} else {
				$value = "'$value'";
			}

			if ($index > 0) {
				$conditions[] = $logic;
			}

			$conditions[] = "`$field` $operator $value";
		}

		$whereExpression = ' WHERE ' . implode(' ', $conditions);
		return $whereExpression;
	}

	public function insert(string $table, array $data): bool
	{
		$columns = implode(', ', array_keys($data));

		$placeholders = implode(
			', ',
			array_map(fn($key) => $key = ":$key", array_keys($data))
		);

		$stmt = static::$db->prepare(
			"INSERT INTO `$table` ($columns) VALUES($placeholders)"
		);

		return $stmt->execute($data);
	}

	public function update(
		string $table,
		array $data,
		array $where,
		string $conditionSeparator = 'AND'
	): bool {
		$setClauses = implode(
			', ',
			array_map(fn($key) => "`$key` = :$key", array_keys($data))
		);

		$whereClauses = implode(
			" $conditionSeparator ",
			array_map(fn($key) => "`$key` = :where_$key", array_keys($where))
		);

		$query = "UPDATE `$table` SET $setClauses WHERE $whereClauses";

		$stmt = static::$db->prepare($query);

		$params = array_merge(
			$data,
			array_combine(
				array_map(fn($key) => "where_$key", array_keys($where)),
				array_values($where)
			)
		);

		return $stmt->execute($params);
	}

	public function count(string $table): int
	{
		$stmt = static::$db->prepare("SELECT COUNT(*) as total FROM `$table`");
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
	}
}
