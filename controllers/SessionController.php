<?php
/** File Created by u1755082 (Kieran Gateley) for module CIS2360 - Relational Databases and Web Integration */
include_once '../models/DatabaseModel.php';
include_once '../models/AuthenticationModel.php';
include_once '../models/FilmDAOImpl.php';

function verifySession() {
    if (session_status() != PHP_SESSION_ACTIVE) { session_start(); }
    if (!isset($_SESSION['db'])) { $_SESSION['db'] = new DatabaseModel(); }
    if (!isset($_SESSION['auth'])) { $_SESSION['auth'] = new AuthenticationModel($_SESSION['db']); }
    if (!isset($_SESSION['films'])) { $_SESSION['films'] = new FilmDAOImpl($_SESSION['db']); }
}

