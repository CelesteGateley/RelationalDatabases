<html lang="en">
<head>
    <title>Film Store Basket</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>
<?php include '../style/header.php'; ?>
<?php
if (isset($_SESSION['basket'])) {
    echo '<table><tr><th>Item</th><th>Amount</th><th>Remove</th></tr>';
    foreach ($_SESSION['basket'] as $id => $amount) {
        echo '<tr><td>'.$_SESSION['films']->getFilmById($id)->getName().'</td><td>'.$amount.'</td>';
        echo '<td><form action="../controllers/BasketController.php" method="post">
              <input type="hidden" name="id" value="'.$id.'"><input type="hidden" name="method" value="remove">
              <input type="submit" value="Remove 1"></form></td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo "<script type='text/javascript'>alert('Your basket is empty!');</script>";
    echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
}
?>
<form action="../controllers/BasketController.php" method="post">
    <input type="password" name="password">
    <input type="hidden" name="method" value="purchase">
    <input type="submit" value="Purchase Basket">
</form>
</body>
</html>