<?php
/** File Created by u1755082 (Kieran Gateley) for module CIS2360 - Relational Databases and Web Integration */
include_once 'SessionController.php';
function logout() : bool {
    verifySession();
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
        unset($_SESSION['email'], $_SESSION['basket'], $_SESSION['verify-timeout']);
        $_SESSION['logged_in'] = false;
        return true;
    }
    return false;
}

if (isset($_POST['logout'])) {
    $res = logout();
    if ($res) {
        echo "<script type='text/javascript'>alert('You have logged out successfully');</script>";
        echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
    } else {
        echo "<script type='text/javascript'>alert('You are not logged in');</script>";
        echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
    }
}