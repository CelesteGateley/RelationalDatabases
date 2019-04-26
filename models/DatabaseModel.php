<?php /** @noinspection MissingReturnTypeInspection */

class DatabaseModel {

    private $conn;
    protected $DB_USER = 'u1755082';
    protected $DB_PASS = '20xCCvC983rD';
    protected $data = 'mysql:host=selene.hud.ac.uk;dbname=u1755082;charset=utf8;';

    public function __construct() {
        try { $this->conn = new PDO($this->data, $this->DB_USER, $this->DB_PASS); }
        catch (PDOException $e) { throw new PDOException($e->getMessage(), (int)$e->getCode()); }
        //$this->updatePasswords();
    }

    private function connect() {
        try { $this->conn = new PDO($this->data, $this->DB_USER, $this->DB_PASS); }
        catch (PDOException $e) { throw new PDOException($e->getMessage(), (int)$e->getCode()); }
    }

    final public function __sleep() : array { return array(); }

    final public function __wakeup()  { $this->connect(); }

    final public function getLastId(): int { return (int)$this->conn->lastInsertId();}

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
    /** @noinspection UnusedFunction */
    private function updatePasswords() : int {
        $res = $this->query('SELECT custid FROM fss_Customer WHERE custpassword = \'pass\';');
        $counter = 0;
        foreach ($res as $id) {
            $query = $this->getPreparedStatement('UPDATE fss_Customer SET custpassword = :pass WHERE custid = :id;');
            $query->execute(['pass' => password_hash('pass', PASSWORD_DEFAULT), 'id' => $id[0]]);
            $counter++;
        }
        return $counter;
    }

}