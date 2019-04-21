<?php /** @noinspection TypeUnsafeComparisonInspection */
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

function logout() : bool {
    verifySession();
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
        unset($_SESSION['email']);
        $_SESSION['logged_in'] = false;
        return true;
    }
    return false;
}