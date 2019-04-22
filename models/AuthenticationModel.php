<?php

class AuthenticationModel {
    private $databaseModel;

    final public function __construct(DatabaseModel $db) {
        $this->databaseModel = $db;
    }

    final public function getUserId(string $email) : int{
        $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_Var($cleanEmail, FILTER_VALIDATE_EMAIL)) { return -1; }
        if (!$this->doesAccountExist($cleanEmail)) { return -1; }
        $res = $this->databaseModel->query('SELECT personid FROM fss_Person WHERE personemail = \''.$email.'\';');
        return $res[0][0];
    }

    final public function getUserEmail(int $id) : string {
        $prepStatement = $this->databaseModel->getPreparedStatement('SELECT personemail FROM fss_Person WHERE personid = :id;');
        $res = $prepStatement->execute([':id' => $id]);
        return $res[0][0];
    }

    private function getPassword(int $id) : string {
        $pArray = $this->databaseModel->query('SELECT custpassword FROM fss_Customer WHERE custid = ' . $id . ';');
        return $pArray[0][0];
    }

    final public function authenticate(string $email, string $password) : bool {
        $id = $this->getUserId($email);
        if ($id === -1) { return false; }
        return $this->authenticateUser($id, $password);
    }

    private function authenticateUser(int $id, string $password) : bool {
        $correctPw = $this->getPassword($id);
        return password_verify($password, $correctPw);
    }

    final public function updatePassword(int $id, string $oldPassword, string $newPassword) : bool {
        if (!$this->authenticateUser($id, $oldPassword)) { return false; }
        $upStatement = $this->databaseModel->getPreparedStatement('UPDATE fss_Customer SET custpassword = :pass WHERE custid = :id;');
        $newPass = password_hash($newPassword, PASSWORD_DEFAULT);
        $upStatement->execute([':pass' => $newPass, ':id' => $id]);
        return true;
    }

    private function doesAccountExist(string $email) : bool {
        $prepStatement = $this->databaseModel->getPreparedStatement('SELECT COUNT(personid) FROM fss_Person WHERE personemail = :email;');
        $prepStatement->execute(['email' => $email]);
        $emailCount = $prepStatement->fetchColumn();
        return $emailCount > 0;
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $fullName
     * @param string $phoneNumber
     * @return int accountId
     * @returns -1 if email invalid
     * @returns -2 if email in use
     * @returns -3 if phone number invalid
     */
    final public function registerUser(string $email, string $password, string $fullName, string $phoneNumber) : int {
        $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        $phoneNumber = str_replace(' ', '', $phoneNumber);
        if (!filter_Var($cleanEmail, FILTER_VALIDATE_EMAIL)) { return -1; }
        if ($this->doesAccountExist($cleanEmail)) { return -2; }
        if (!preg_match('(0|\+44)[1-9][0-9]{9}', $phoneNumber)) { return -3; }

        $personPrep = $this->databaseModel->getPreparedStatement('INSERT INTO fss_Person (personname, personphone, personemail) VALUES (:name, :phone, :email);');
        $personPrep->execute(['name' => addslashes($fullName), 'phone' => $phoneNumber, 'email' => $cleanEmail]);

        $custId = $this->getUserId($cleanEmail);

        $currDate = date('y') . '-' . date('m') . '-' . date('d');

        $customerPrep = $this->databaseModel->getPreparedStatement('INSERT INTO fss_Customer (custid, custregdate, custenddate, custpassword) VALUES (:id, :regdate, \'0000-00-00\', :pass);');
        $customerPrep->execute(['id' => $custId, 'regdate' => $currDate, 'pass' => password_hash($password, PASSWORD_DEFAULT)]);

        return $custId;
    }
}