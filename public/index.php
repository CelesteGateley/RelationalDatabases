<!-- File Created by u1755082 (Kieran Gateley) for module CIS2360 - Relational Databases and Web Integration -->
<html lang="en">
<?php ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); ?>
<head>
    <title>Film store</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>
<?php include '../style/header.php'; ?>
<p>
<div style="display: block; text-align: center; color: red; width: 100%">Films highlighted in red are out of stock!</div>
<p>
<?php include_once '../controllers/FilmTable.php'; ?>
<?php include_once '../style/footer.php'; ?>
</body>
</html>