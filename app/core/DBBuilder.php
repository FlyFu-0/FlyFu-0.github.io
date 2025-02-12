<?php

namespace Core;

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

    public function setInsert(): self
    {
        $this->db->setInsert();
        return $this;
    }

    public function setTable(string $table): self
    {
        $this->db->setTable($table);
        return $this;
    }

    public function setInsertData(array $data): self
    {
        $this->db->setInsertData($data);
        return $this;
    }

    public function setUpdate(string $table, array $set): self
    {
        $this->db->setUpdate($table, $set);
        return $this;
    }

    public function getQuery(): string
    {
        return $this->db->getQuery();
    }

    public function execute(): array
    {
        return $this->getDB()->execute();
    }
}