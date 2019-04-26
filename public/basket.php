<html lang="en">
<head>
    <title>Film Store Basket</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>
<?php include '../style/header.php'; ?>
<p>
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
<p>
<form action="../controllers/BasketController.php" method="post">
    <input type="password" name="password" placeholder="Password">
    <div style="padding-left: 10px;">
        <p style="padding-left: 5px;">
        <input type="text" name="card_info[cardNo]" placeholder="Card Number" size="10">
        <select name="card_info[cardType]">
            <option value="American Express">American Express</option>
            <option value="Visa" selected>Visa</option>
            <option value="Visa Express">Visa Express</option>
            <option value="Switch">Switch</option>
            <option value="Solo">Solo</option>
            <option value="Mastercard">Mastercard</option>
        </select>
        <input type="text" name="card_info[cardExp][expDay]" placeholder="Day" size="3">
        <input type="text" name="card_info[cardExp][expMonth]" placeholder="Month" size="3">
        </p>
        <p style="padding-left: 5px;">
        <input type="hidden" name="method" value="purchase">
        </p>
    </div>
    <input type="submit" value="Purchase Basket">
</form>
</body>
</html>