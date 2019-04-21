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
        $this->updatePasswords();
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


    private function updatePasswords() : int {
        $res = $this->query('SELECT custid FROM fss_Customer WHERE custpassword = \'pass\';');
        $counter = 0;
        foreach ($res as $id) {
            $query = $this->getPreparedStatement('UPDATE fss_Customer SET custpassword = :pass WHERE custid = :id;');
            $query->execute(['pass' => password_hash('pass', PASSWORD_DEFAULT), 'id' => $id]);
            $counter++;
        }
        return $counter;
    }

}