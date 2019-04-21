<?php
function login(string $email, string $password, AuthenticationModel $auth) : bool {
    $isAuthenticated = $auth->authenticate($email, $password);
    if ($isAuthenticated) {
        /** @noinspection TypeUnsafeComparisonInspection */
        if (session_status() == PHP_SESSION_ACTIVE) { session_destroy(); }
        session_start();
        $_SESSION['email'] = $email;
        $_SESSION['logged_in'] = true;
        return true;
    }
    return false;
}

function logout() : bool {
    /** @noinspection TypeUnsafeComparisonInspection */
    $sessionStatus = session_status() == PHP_SESSION_ACTIVE;
    if ($sessionStatus) { session_destroy(); }
    return $sessionStatus;
}