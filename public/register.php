<!-- File Created by u1755082 (Kieran Gateley) for module CIS2360 - Relational Databases and Web Integration -->
<html lang="en">
<head>
    <title>Film Store Registration</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>
<?php
include_once '../controllers/SessionController.php';
verifySession();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    echo "<script type='text/javascript'>alert('You are already logged in!');</script>";
    echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
}
?>
<h1>Register for our Film Store</h1>
Use the form below to register for our site!
<p>
<div style="padding-left: 2px;">
    <form action="../controllers/RegistrationController.php" method="post">
        <div style="display: inline-block;">
            Full Name:<br>
            <input type="text" name="name"><br><br>
            Email:<br>
            <input type="text" name="email"><br><br>
            Password:<br>
            <input type="password" name="password"><br><br>
            Confirm Password:<br>
            <input type="password" name="conf_password"><br>
        </div>
        <div style="display: inline-block; padding-left: 5px; ">
            Phone Number:<br>
            <input type="text" name="phone"><br><br>
            Street:<br>
            <input type="text" name="street"><br><br>
            City:<br>
            <input type="text" name="city"><br><br>
            Postcode:<br>
            <input type="text" name="postcode"><br>
        </div>
        <br><br>
        <input type="submit" value="Register">
    </form>
    <form action="index.php"><input type="submit" value="Previous Page"></form>
</div>
<?php include_once '../style/footer.php'; ?>
</body>
</html>