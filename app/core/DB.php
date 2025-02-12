<?php

namespace Core;

class DB
{
	const ORDER_ASC = 'ASC';
	const ORDER_DESC = 'DESC';
	protected \PDO $db;
	private $action;
	private $from;
	private $join;
	private $where;
	private $order;
	private $limit;
	private $paged;
	private $query;

	public function __construct()
	{
		$this->db = DBConnector::getInstance();
	}

	public function setSelect(array $columns): self
	{
		$this->action = "SELECT " . implode(', ', $columns);
		return $this;
	}

	public function setInsert(): self
	{
		$this->action = "INSERT INTO";
		return $this;
	}

	public function setInsertData(array $data): self
	{
		$columns = "`" . implode("`, `", array_keys($data)) . "`";
		$values = "'" . implode("', '", array_values($data)) . "'";

		$this->action .= " ($columns) VALUES ($values)";

		return $this;
	}

	public function setUpdate($table, array $set): self
	{
		$this->action = "UPDATE `$table` SET ";

		$setParts = [];
		foreach ($set as $column => $value) {
			$setParts[] = "`$column` = $value";
		}

		$this->action .= implode(', ', $setParts);

		var_dump($this->action);

		return $this;
	}

	public function setWhere(array $where): string
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

		return $this->where = ' WHERE ' . implode(' ', $conditions);
	}

	public function setTable($table): self
	{
		$this->action .= " `$table`";
		return $this;
	}

	public function setFrom($table): self
	{
		$this->from = " FROM `$table`";
		return $this;
	}

	public function setJoin($joins): self
	{
		foreach ($joins as $joinTable => $onCondition) {
			$this->join .= " JOIN `$joinTable` ON $onCondition";
		}
		return $this;
	}

	public function setOrder(
		string $column,
		string $order = self::ORDER_ASC
	): DB {
		if (!$this->order) {
			$this->order = " ORDER BY $column $order";
		} else {
			$this->order .= ", $column $order";
		}
		return $this;
	}

	public function execute(): array
	{
		$query = $this->getQuery();

		$stmt = $this->db->prepare($query);

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getQuery(): string
	{
		return $this->query ??= "
			$this->action 
			$this->from
			$this->join
			$this->where
			$this->order
			" . ($this->paged ?? $this->limit);
	}

	public function setLimit(int $limit): self
	{
		$this->limit = " LIMIT $limit";
		return $this;
	}

	public function setPaged(int $start, int $countOnPage): self
	{
		$this->paged = " LIMIT $start, $countOnPage";
		return $this;
	}

	public function __get(string $name)
	{
		return $this->$name;
	}

}