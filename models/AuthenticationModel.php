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

    final public function updateAddress(string $addId, string $street, string $city, string $postcode) {
        $prepStm = $this->databaseModel->getPreparedStatement('UPDATE fss_Address SET addstreet = :street, addcity = :city, addpostcode = :postcode WHERE addid = :id;');
        $prepStm->execute(['id' => $addId, 'street' => $street, 'city' => $city, 'postcode' => $postcode]);

    }

    final public function setAddress(string $email, string $street, string $city, string $postcode) {
        $userId = $this->getUserId($email);
        $prepStm = $this->databaseModel->getPreparedStatement('INSERT INTO fss_Address (addstreet, addcity, addpostcode) VALUES (:street, :city, :postcode);');
        $prepStm->execute(['street' => $street, 'city' => $city, 'postcode' => $postcode]);
        $addId = $this->databaseModel->getLastId();
        $prepStm = $this->databaseModel->getPreparedStatement('INSERT INTO fss_CustomerAddress VALUES (:addid, :custid);');
        $prepStm->execute(['addid' => $addId, 'custid' => $userId]);
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

    final public function updateInfo(string $id, string $name, string $phoneNo, string $email) : int {
        $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        $phoneNo = str_replace(' ', '', $phoneNo);
        if (!filter_Var($cleanEmail, FILTER_VALIDATE_EMAIL)) { return -1; }
        if (!preg_match('/(0|\+44)[1-9][0-9]{9}/', $phoneNo)) { return -2; }
        $prepStm = $this->databaseModel->getPreparedStatement('UPDATE fss_Person SET personname = :personname, personemail = :email, personphone = :phone WHERE personid = :id;');
        $prepStm->execute(['id' => $id, 'personname' => $name, 'email' => $email, 'phone' => $phoneNo]);
        return 0;
    }

    private function doesAccountExist(string $email) : bool {
        $prepStatement = $this->databaseModel->getPreparedStatement('SELECT COUNT(personid) FROM fss_Person WHERE personemail = :email;');
        $prepStatement->execute(['email' => $email]);
        $emailCount = $prepStatement->fetchColumn();
        return $emailCount > 0;
    }

    final public function getAddressId(string $email) : int {
        $customerId = $this->getUserId($email);
        $prepStatement = $this->databaseModel->getPreparedStatement('SELECT COUNT(addid) FROM fss_CustomerAddress WHERE custid = :id;');
        $prepStatement->execute(['id' => $customerId]);
        $hasAddress = $prepStatement->fetchColumn() > 0;
        if ($hasAddress) {
            $prepStatement = $this->databaseModel->getPreparedStatement('SELECT addid FROM fss_CustomerAddress WHERE custid = :id;');
            $prepStatement->execute(['id' => $customerId]);
            return $prepStatement->fetchAll()[0][0];
        }
        return -1;
    }

    final public function getCardInfo(string $email) : array {
        $prepStm = $this->databaseModel->getPreparedStatement('SELECT COUNT(*) FROM fss_CardPayment, fss_OnlinePayment WHERE fss_OnlinePayment.payid = fss_CardPayment.payid AND fss_OnlinePayment.custid = :id ORDER BY fss_CardPayment.payid DESC LIMIT 1;');
        $prepStm->execute(['id' => $this->getUserId($email)]);
        if ($prepStm->fetchColumn() > 0) {
            $prepStm = $this->databaseModel->getPreparedStatement('SELECT fss_CardPayment.cno, fss_CardPayment.ctype, fss_CardPayment.cexpr FROM fss_CardPayment, fss_OnlinePayment WHERE fss_OnlinePayment.payid = fss_CardPayment.payid AND fss_OnlinePayment.custid = :id ORDER BY fss_CardPayment.payid DESC LIMIT 1;');
            $prepStm->execute(['id' => $this->getUserId($email)]);
            $res = $prepStm->fetchAll();
            $retVal = array();
            $retVal['cno'] = $res[0][0];
            $retVal['ctype'] = $res[0][1];
            $retVal['expmo'] = explode(':', $res[0][2])[0];
            $retVal['expday'] = explode(':', $res[0][2])[1];
            return $retVal;
        }
        return array();

    }

    final public function getName(string $email) : string {
        $userId = $this->getUserId($email);
        $name = $this->databaseModel->query('SELECT personname FROM fss_Person WHERE personid = ' . $userId . ';');
        return $name[0][0];
    }

    final public function getPhoneNumber(string $email) : string {
        $userId = $this->getUserId($email);
        $name = $this->databaseModel->query('SELECT personphone FROM fss_Person WHERE personid = ' . $userId . ';');
        return $name[0][0];
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
        if (!preg_match('/(0|\+44)[1-9][0-9]{9}/', $phoneNumber)) { return -3; }

        $personPrep = $this->databaseModel->getPreparedStatement('INSERT INTO fss_Person (personname, personphone, personemail) VALUES (:name, :phone, :email);');
        $personPrep->execute(['name' => addslashes($fullName), 'phone' => $phoneNumber, 'email' => $cleanEmail]);

        $custId = $this->getUserId($cleanEmail);

        $currDate = date('y') . '-' . date('m') . '-' . date('d');

        $customerPrep = $this->databaseModel->getPreparedStatement('INSERT INTO fss_Customer (custid, custregdate, custendreg, custpassword) VALUES (:id, :regdate, \'3019-01-01\', :pass);');
        $customerPrep->execute(['id' => $custId, 'regdate' => $currDate, 'pass' => password_hash($password, PASSWORD_DEFAULT)]);

        return $custId;
    }
}