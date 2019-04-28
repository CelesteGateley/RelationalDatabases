<?php
/** File Created by u1755082 (Kieran Gateley) for module CIS2360 - Relational Databases and Web Integration */
function displayOrders(string $email) {
    verifySession();
    $accId = $_SESSION['auth']->getUserId($email);
    $prepStm = $_SESSION['db']->getPreparedStatement('SELECT fss_Payment.payid, fss_Payment.paydate, RIGHT(fss_CardPayment.cno, 4) AS "Card Number", fss_Payment.amount FROM fss_CardPayment, fss_Payment, fss_OnlinePayment WHERE fss_Payment.payid = fss_OnlinePayment.payid AND fss_CardPayment.payid = fss_OnlinePayment.payid AND fss_OnlinePayment.custid = :id ORDER BY fss_Payment.paydate DESC;');
    $prepStm->execute(['id' => $accId]);
    echo '<table><tr><th>Date</th><th>Card Number</th><th>Amount</th><th>More Info</th></tr>';
    foreach ($prepStm->fetchAll() as $row) {
        echo '<tr>';
        $expDate = explode('-', $row[1]);
        $formDate = $expDate[2] . '-' . $expDate[1] . '-' . $expDate[0];
        echo '<td>' . $formDate . '</td>';
        echo '<td>******' . $row[2] . '</td>';
        echo '<td>£' . $row[3] . '</td>';
        echo '<td><form action="../public/orderinfo.php" method="post"><input type="hidden" name="order_id" value="'.$row[0].'"><input type="submit" value="Show Details"></form></td>';
        echo '</tr>';
    }
}

function displayOrder(string $email, string $paymentId) {
    verifySession();
    $prepStatement = $_SESSION['db']->getPreparedStatement('SELECT COUNT(personid) FROM fss_Person WHERE personemail = :email;');
    $prepStatement->execute(['email' => $email]);
    $count = $prepStatement->fetchColumn();
    if ($count > 0) {
        $prepStm = $_SESSION['db']->getPreparedStatement('SELECT filmid, price FROM fss_FilmPurchase WHERE payid = :id');
        $prepStm->execute(['id' => $paymentId]);
        echo '<table><tr><th>Film Name</th><th>Cost</th></tr>';
        foreach ($prepStm->fetchAll() as $row) {
            $film = $_SESSION['films']->getFilmById($row[0]);
            echo '<tr><td>' . $film->getName() . '</td><td>' . $row[1] . '</td></tr>';
        }
    } else {
        echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
    }

}