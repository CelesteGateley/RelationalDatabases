<?php
include '../controllers/LoginController.php';
$res = logout();
if ($res) {
    echo "<script type='text/javascript'>alert('You have logged out successfully');</script>";
    echo "<script type='text/javascript'>location.href = 'index.php';</script>";
} else {
    echo "<script type='text/javascript'>alert('You are not logged in');</script>";
    echo "<script type='text/javascript'>location.href = 'index.php';</script>";
}