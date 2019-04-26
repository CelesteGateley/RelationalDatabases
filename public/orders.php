<html lang="en">
<head>
    <title>Film store</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>
<?php include '../style/header.php'; ?>
<p>
<?php
include '../controllers/OrderController.php';
if (isset($_SESSION['email'], $_SESSION['logged_in']) && $_SESSION['logged_in']) {
    displayOrders($_SESSION['email']);
} else {
    echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
}
 ?>
</body>
</html>
