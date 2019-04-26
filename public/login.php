<html lang="en">
<head>
    <title>Film Store Login</title>
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
<form action="../controllers/LoginController.php" method="post">
    Email:<br>
    <?php
        if (isset($_SESSION['email'])) { echo '<input type="text" name="email" value="'.$_SESSION['email'].'"><br>'; }
        else { echo '<input type="text" name="email"><br>'; }
    ?>
    Password:<br>
    <input type="password" name="password"><br>
    <input type="submit" value="Login">
</form>
<form action="index.php"><input type="submit" value="Previous Page"></form>
</body>
</html>