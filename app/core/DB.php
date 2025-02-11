<?php

namespace Core;

class DB
{
	const ORDER_ASC = 'ASC';
	const ORDER_DESC = 'DESC';
	protected \PDO $db;
	private $select;
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

	public function setSelect($columns): self
	{
		$this->select = "SELECT " . implode(', ', $columns);
		return $this;
	}

	public function setInsert($table, $data): self
	{
		$columns = "`" . implode("`, `", array_keys($data)) . "`";

		$values = "'" . implode("', '", array_values($data)) . "'";

		$this->query = "INSERT INTO `$table` ($columns) VALUES ($values)";

//		var_dump($this->query);

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

	public function execute()
	{
		$this->query ??= "
			$this->select 
			$this->from
			$this->join
			$this->where
			$this->order
			" . ($this->paged ?? $this->limit);

		$stmt = $this->db->prepare($this->query);
		$stmt->execute();

		var_dump($this->query);
		echo "<script>console.log($this->query)</script>";

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