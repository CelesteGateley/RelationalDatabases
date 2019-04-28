<!-- File Created by u1755082 (Kieran Gateley) for module CIS2360 - Relational Databases and Web Integration -->
<html lang="en">
<head>
    <title>Film store</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>
<?php include '../style/header.php'; ?>
<p>
<h1>Previous Orders</h1>
Below are all of your previous orders from our store. <br>For a list of films in the order, click the 'Show Details' button.
</p>
<p>
<?php
include '../controllers/OrderController.php';
if (isset($_SESSION['email'], $_SESSION['logged_in']) && $_SESSION['logged_in']) {
    displayOrders($_SESSION['email']);
} else {
    echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
}
 ?>
<?php include_once '../style/footer.php'; ?>
</body>
</html>
