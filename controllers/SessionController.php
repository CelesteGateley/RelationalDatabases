<?php /** @noinspection TypeUnsafeComparisonInspection */
include '../models/DatabaseModel.php';
include '../models/AuthenticationModel.php';

function setupSession() : bool {
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
        $_SESSION['db'] = new DatabaseModel();
        $_SESSION['auth'] = new AuthenticationModel($_SESSION['db']);
        return true;
    }
    return false;
}

function verifySession() {
    if (session_status() != PHP_SESSION_ACTIVE) { session_start(); }
    if (!isset($_SESSION['db'])) { $_SESSION['db'] = new DatabaseModel(); }
    if (!isset($_SESSION['auth'])) { $_SESSION['auth'] = new AuthenticationModel($_SESSION['db']); }
}