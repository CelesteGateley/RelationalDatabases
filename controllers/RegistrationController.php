<?php
include 'SessionController.php';
function register(string $email, string $password, string $name, string $phone, AuthenticationModel $auth) : int {
    $res = $auth->registerUser($email, $password, $name, $phone);
    if ($res >= 0) {
        $res = 0;
        session_start();
        $_SESSION['email'] = $email;
        $_SESSION['logged_in'] = false;
    }
    return $res;
}