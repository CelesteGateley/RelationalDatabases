<?php
/** File Created by u1755082 (Kieran Gateley) for module CIS2360 - Relational Databases and Web Integration */
include 'SessionController.php';
function login(string $email, string $password) : bool {
    verifySession();
    $isAuthenticated = $_SESSION['auth']->authenticate($email, $password);
    if ($isAuthenticated) {
        $_SESSION['email'] = $email;
        $_SESSION['logged_in'] = true;
        return true;
    }
    return false;
}


if (isset($_POST['email'], $_POST['password'])) {
    $res = login($_POST['email'], $_POST['password']);
    if ($res) {
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['logged_in'] = true;
        echo "<script type='text/javascript'>alert('You have logged in successfully');</script>";
        echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";

    } else {
        echo "<script type='text/javascript'>alert('Invalid Email or Password');</script>";
        echo "<script type='text/javascript'>location.href = '../public/login.php';</script>";
    }
} else {
    echo "<script type='text/javascript'>alert('Please enter an Email and Password!');</script>";
    echo "<script type='text/javascript'>location.href = '../public/login.php';</script>";
}
