<?php
/** File Created by u1755082 (Kieran Gateley) for module CIS2360 - Relational Databases and Web Integration */
include 'SessionController.php';
function register(string $email, string $password, string $name, string $phone, AuthenticationModel $auth) : int {
    $res = $auth->registerUser($email, $password, $name, $phone);
    if ($res >= 0) {
        $res = 0;
        verifySession();
        $_SESSION['email'] = $email;
        $_SESSION['logged_in'] = false;
    }
    return $res;
}

if (isset($_POST['email'], $_POST['password'], $_POST['conf_password'], $_POST['name'], $_POST['phone'], $_POST['street'], $_POST['city'], $_POST['postcode'])) {
     if ($_POST['password'] === $_POST['conf_password']) {
         verifySession();
         $res = register($_POST['email'], $_POST['password'], $_POST['name'], $_POST['phone'], $_SESSION['auth']);
         switch ($res) {
             case -1:
                 echo "<script type='text/javascript'>alert('That email address is invalid!');</script>";
                 echo "<script type='text/javascript'>location.href = '../public/register.php';</script>";
                 break;
             case -2:
                 echo "<script type='text/javascript'>alert('Email address already in use!');</script>";
                 echo "<script type='text/javascript'>location.href = '../public/register.php';</script>";
                 break;
             case -3:
                 echo "<script type='text/javascript'>alert('Phone number invalid!');</script>";
                 echo "<script type='text/javascript'>location.href = '../public/register.php';</script>";
                 break;
             default:
                 $_SESSION['auth']->setAddress($_POST['email'], $_POST['street'], $_POST['city'], $_POST['postcode']);
                 echo "<script type='text/javascript'>alert('You have registered successfully!');</script>";
                 echo "<script type='text/javascript'>location.href = '../public/login.php';</script>";
         }
     } else {
         echo "<script type='text/javascript'>alert('Passwords do not match!');</script>";
         echo "<script type='text/javascript'>location.href = '../public/register.php';</script>";
     }
} else {
    echo "<script type='text/javascript'>alert('Please enter all the required information!!');</script>";
    echo "<script type='text/javascript'>location.href = '../public/register.php';</script>";
}