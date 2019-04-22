<html lang="en">
<head>
    <title>Bookstore Registration</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>
<?php
include_once '../controllers/SessionController.php';
setupSession();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    echo "<script type='text/javascript'>alert('You are already logged in!');</script>";
    echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
}
?>
<form action="../controllers/RegistrationController.php" method="post">
    Full Name:<br>
    <input type="text" name="name"><br>
    Phone Number:<br>
    <input type="text" name="phone"><br>
    Email:<br>
    <input type="text" name="email"><br>
    Password:<br>
    <input type="password" name="password"><br>
    Confirm Password:<br>
    <input type="password" name="conf_password"><br>
    <input type="submit" value="Register">
</form>
<form action="index.php"><input type="submit" value="Previous Page"></form>
</body>
</html>