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
<h1>Login to our Film Store</h1>
Use the form below to login to our Film Store!
<br><br>
<form action="../controllers/LoginController.php" method="post">
    Email:<br>
    <?php
        if (isset($_SESSION['email'])) { echo '<input type="text" name="email" value="'.$_SESSION['email'].'"><br><br>'; }
        else { echo '<input type="text" name="email"><br><br>'; }
    ?>
    Password:<br>
    <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>
<form action="index.php"><input type="submit" value="Previous Page"></form>
<?php include_once '../style/footer.php'; ?>
</body>
</html>