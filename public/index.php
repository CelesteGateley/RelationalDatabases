<html lang="en">
<head>
    <title>Bookstore</title>
</head>
<body>
<?php
    setupSession();
?>
<form action="login.php" method="post">
    Email:<br>
    <input type="text" name="email"><br>
    Password:<br>
    <input type="password" name="password"><br>
    <input type="submit">
</form>
</body>
</html>