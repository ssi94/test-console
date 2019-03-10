<?php

namespace App;

class DatabaseAdapter 
{
    protected $connection;
    protected $transactionStarted = false;

    public function __construct(\PDO $connection) {
        $this->connection = $connection;
    }

    public function insertItem($item) {
        return $this->connection
            ->prepare("insert into product (code, name, description, stock, price, discontinued_at) VALUES (:code, :name, :description, :stock, :price, :discontinued_at)")
            ->execute($item);
    }

    public function beginTransaction() {
        $this->connection->beginTransaction();
        $this->transactionStarted = true;
    }

    public function rollBack() {
        if($this->transactionStarted) {
            $this->connection->rollBack();
        }
    }
}