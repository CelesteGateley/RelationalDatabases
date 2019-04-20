<?php

class DatabaseModel {

    private $conn;
    protected $DB_NAME = 'u1755082';
    protected $DB_USER = 'u1755082';
    protected $DB_PASS = '20xCCvC983rD';
    protected $DB_HOST = 'localhost';

    public function __construct() {
        $data = 'mysql:host=' . $this->DB_HOST . ';dbname=' . $this->DB_NAME . ';charset=utf8mb5;';
        try { $this->conn = new PDO($data, $this->DB_USER, $this->DB_PASS); }
        catch (PDOException $e) { throw new PDOException($e->getMessage(), (int)$e->getCode()); }
    }

    final public function  getConnection() : PDO { return $this->conn; }

    final public function execute(string $statement) : int {
        return $this->conn->exec($statement);
    }

    final public function getPreparedStatement(string $statement) : PDOStatement{
        return $this->conn->prepare($statement);
    }

    final public function query(string $statement) : array {
        return $this->conn->query($statement)->fetchAll();
    }

    final public function queryCount(string $statement) : int {
        return $this->conn->query($statement)->rowCount();
    }

}