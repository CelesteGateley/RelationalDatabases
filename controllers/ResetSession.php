<?php
include_once 'SessionController.php';
if (isset($_POST['method']) && $_POST['method'] === 'reset') {
    session_start();
    if (session_status() === PHP_SESSION_ACTIVE) { session_destroy(); }
    verifySession();
}
echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";