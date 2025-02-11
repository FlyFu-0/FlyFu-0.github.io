<?php

namespace Core;

use http\Params;

class DBBuilder
{
	private DB $db;

	public function __construct()
	{
		$this->db = new DB();
	}

	public function getDB(): DB
	{
		return $this->db;
	}

	public function setSelect(array $columns): self
	{
		$this->getDB()->setSelect($columns);
		return $this;
	}

	public function setFrom(string $table): self
	{
		$this->getDB()->setFrom($table);
		return $this;
	}

	public function setJoin(array $joins): self
	{
		$this->getDB()->setJoin($joins);
		return $this;
	}

	public function setOrder(string $column, string $order = DB::ORDER_ASC): self
	{
		$this->getDB()->setOrder($column, $order);
		return $this;
	}

	public function setLimit(int $limit = 100): self
	{
		$this->db->setLimit($limit);
		return $this;
	}

	public function setPaged(int $start, int $countOnPage): self
	{
		$this->db->setPaged($start, $countOnPage);
		return $this;
	}

	public function setWhere(array $where): self
	{
		$this->db->setWhere($where);
		return $this;

	}

	public function setInsert(string $table, array $data)
	{
		$this->db->setInsert($table, $data);
		return $this;
	}

	public function execute(): array
	{
		return $this->getDB()->execute();
	}
}