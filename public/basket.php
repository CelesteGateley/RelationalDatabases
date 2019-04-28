<!-- File Created by u1755082 (Kieran Gateley) for module CIS2360 - Relational Databases and Web Integration -->
<html lang="en">
<head>
    <title>Film Store Basket</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>
<?php include '../style/header.php'; ?>
<p>
    <h1>Your Basket</h1>
Below are all of the items you have put into your basket.
</p>
<?php
if (isset($_SESSION['email'])) {
    $cardInfo = $_SESSION['auth']->getCardInfo($_SESSION['email']);
}
if (isset($_SESSION['basket'])) {
    echo '<table><tr><th>Item</th><th>Cost</th><th>Amount</th><th>Remove</th></tr>';
    foreach ($_SESSION['basket'] as $id => $amount) {
        echo '<tr><td>'.$_SESSION['films']->getFilmById($id)->getName().'</td><td>£'.($_SESSION['films']->getFilmById($id)->getCost()*$amount).'</td></ts><td>'.$amount.'</td>';
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
<p>
<form action="../controllers/BasketController.php" method="post">
    <input type="password" name="password" placeholder="Password">
    <div style="padding-left: 10px;">
        <?php if (!empty($cardInfo)) { echo '<p><input type="checkbox" name="use_previous" value="yes">Use Previous Details';} ?>
        <p style="padding-left: 5px;">

        <input type="text" name="card_info[cardNo]" placeholder="Card Number" size="10">
        <input type="text" name="card_info[cardType]" placeholder="Card Type" size="10">
        <input type="text" name="card_info[cardExp][expDay]" placeholder="Day" size="3">
        <input type="text" name="card_info[cardExp][expMonth]" placeholder="Month" size="3">
        </p>
        <p style="padding-left: 5px;">
        <input type="hidden" name="method" value="purchase">
        </p>
    </div>
    <input type="submit" value="Purchase Basket">
</form>
<?php include_once '../style/footer.php'; ?>
</body>
</html>