<?php
if (isset($_POST['email'], $_POST['password'])) {
    $res = login($_POST['email'], $_POST['password']);
    if ($res) {
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['logged_in'] = true;
        echo "<script type='text/javascript'>alert('You have logged in successfully');</script>";
    } else {
        echo "<script type='text/javascript'>alert('Invalid Email or Password');</script>";
    }
} else {
    echo "<script type='text/javascript'>alert('Please enter an Email and Password!');</script>";
}
header('Location: index.php');