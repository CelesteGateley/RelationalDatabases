<html lang="en">
<head>
    <title>Film Store Verification</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>
<?php include_once '../style/header.php'; ?>
<p>
    <form action="../controllers/AccountModificationController.php" method="post">
        <input type="password" name="value">
        <input type="hidden" name="method" value="verify">
        <input type="submit" value="Verify Password">
    </form>
</body>
</html>