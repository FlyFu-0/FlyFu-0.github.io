<?php

namespace Core;

class DB
{
	const ORDER_ASC = 'ASC';
	const ORDER_DESC = 'DESC';

	const JOIN_LEFT = 'LEFT';
	const JOIN_RIGHT = 'RIGHT';
	const JOIN_OUTER = 'FULL';
	const JOIN_INNER = 'INNER';

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
		$valueEscaped = str_replace("'", "\'", array_values($data));
		$valueEscaped = str_replace("\\", "\\\\", array_values($data));

		$columns = "`" . implode("`, `", array_keys($data)) . "`";
		$values = "'" . implode("', '", $valueEscaped) . "'";

		$this->action .= " ($columns) VALUES ($values)";

		var_dump($this->getQuery());
		return $this;
	}

	public function getQuery(): string
	{
		$this->query = match (true) {
			$this->isActionStarts('SELECT') => "
				{$this->action} 
				FROM {$this->table}
				{$this->join}
				{$this->where}
				{$this->order}
				" . ($this->paged ?? $this->limit),
			$this->isActionStarts(['INSERT', 'UPDATE']) => $this->action,
		};

		return $this->query;
	}

	private function isActionStarts($action): bool
	{
		if (!is_array($action)) {
			return str_starts_with($this->action, $action);
		}

		foreach ($action as $value) {
			if (str_starts_with($this->action, $value)) {
				return true;
			}
		}


		return false;
	}

	public function setUpdate(array $set): self
	{
		$this->action = "UPDATE `{$this->table}` SET ";

		$setParts = [];
		foreach ($set as $column => $value) {
			$valueEscaped = str_replace("'", "\'", $value);
			var_dump($valueEscaped);
			$setParts[] = "`$column` = $valueEscaped";
		}

		$this->action .= implode(', ', $setParts);

		return $this;
	}

	public function addWhere(array $where): self
	{
		$conditions = [];

		foreach ($where as $condition) {
			$logic = strtoupper($condition['logic'] ?? 'AND');
			$field = $condition['field'];
			$operator = $condition['operator'] ?? '=';
			$value = $condition['value'];

			if (strtoupper($operator) === 'IN' && is_array($value)) {
				$value = "('" . implode("', '", $value) . "')";
			} else {
				$value = "'$value'";
			}

			if ($this->where) {
				$conditions[] = $logic;
			} else {
				$this->where = ' WHERE ';
			}

			$conditions[] = "`$field` $operator $value ";
		}

		$this->where .= implode(' ', $conditions);
		return $this;
	}

	public function setTable($table): self
	{
		$this->table = "`$table`";
		return $this;
	}

	public function setJoin($joins, string $joinType = DB::JOIN_INNER): self
	{
		foreach ($joins as $joinTable => $onCondition) {
			$this->join .= "$joinType JOIN `$joinTable` ON $onCondition";
		}
		return $this;
	}

	public function addOrder(
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