<html lang="en">
<head>
    <title>Bookstore</title>
</head>
<body>
<?php
    include '../controllers/SessionController.php';
    setupSession();
    if (isset($_SESSION['email'])) {
        echo "You are logged in as " . $_SESSION['email'] . "<br>";
    } else {
        echo "You are not logged in";
    }
?>
<form action="login.php" method="post">
    Email:<br>
    <input type="text" name="email"><br>
    Password:<br>
    <input type="password" name="password"><br>
    <input type="submit" value="Login">
</form>
<form action="logout.php"><input type="submit" value="logout"></form>
</body>
</html>