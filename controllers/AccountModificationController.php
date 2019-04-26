<?php
function verify(string $password) : bool {
    verifySession();
    if (isset($_SESSION['logged_in'], $_SESSION['email']) && $_SESSION['logged_in'] && $_SESSION['auth']->authenticate($_SESSION['email'], $password)) {
        $_SESSION['verify-timeout'] = time() + 120;
        return true;
    }
    return false;
}

if (isset($_POST['method'], $_POST['value'])) {
    if ($_POST['method'] === 'verify') {
        $verification = verify($_POST['value']);
        if ($verification) {
            echo "<script type='text/javascript'>location.href = '../public/account.php';</script>";
        }
        echo "<script type='text/javascript'>alert('Incorrect Password!');</script>";
        echo "<script type='text/javascript'>location.href = '../public/verify.php';</script>";
    } else if ($_POST['method'] === 'change_password') {
        // TODO: Implement
    }
}