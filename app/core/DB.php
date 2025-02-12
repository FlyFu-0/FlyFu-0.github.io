<?php

namespace Core;

class DB
{
	const ORDER_ASC = 'ASC';
	const ORDER_DESC = 'DESC';
	protected \PDO $db;
	private $action = '';
	private $table;
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
		$this->action = "INSERT INTO {$this->table}";
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

		return $this;
	}

	public function setWhere(array $where): self
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

		$this->where = ' WHERE ' . implode(' ', $conditions);
		return $this;
	}

	public function setTable($table): self
	{
		$this->table .= "`$table`";
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
		$this->query ??= match (true) {
			$this->isActionStart('SELECT') => "
				{$this->action} 
				FROM {$this->table}
				{$this->join}
				{$this->where}
				{$this->order}
				" . ($this->paged ?? $this->limit),
			$this->isActionStart('INSERT'), $this->isActionStart('UPDATE') => $this->action,
		};

		return $this->query;
	}

	private function isActionStart($action): bool
	{
		return str_starts_with($this->action, $action);
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