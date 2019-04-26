<?php
include 'SessionController.php';
function addToBasket($id) {
    verifySession();
    if (!isset($_SESSION['basket'])) {
        $_SESSION['basket'] = array();
    }
    if (!isset($_SESSION['basket'][$id])) {
        $_SESSION['basket'][$id] = 1;
    } else if ($_SESSION['basket'][$id] + 1 <= $_SESSION['films']->getFilmById($id)->getStock()) {
        $_SESSION['basket'][$id] += 1;
    } else {
        echo "<script type='text/javascript'>alert('You cannot add any more of this item!');</script>";
        echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
    }
    //echo "<script type='text/javascript'>alert('Item added to basket!');</script>";
    echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
}

function removeFromBasket($id) {
    verifySession();
    if (isset($_SESSION['basket'][$id])) {
        $_SESSION['basket'][$id] -= 1;
        if ($_SESSION['basket'][$id] <= 0) { unset($_SESSION['basket'][$id]); }
        if (empty($_SESSION['basket'])) { unset($_SESSION['basket']); }
    } else {
        echo "<script type='text/javascript'>alert('That item is not in your basket!');</script>";
        echo "<script type='text/javascript'>location.href = '../public/basket.php';</script>";
    }
    //echo "<script type='text/javascript'>alert('Item removed to basket!');</script>";
    if (isset($_SESSION['basket'])) {
        echo "<script type='text/javascript'>location.href = '../public/basket.php';</script>";
    } else {
        echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
    }
}

function purchaseBasket(string $password) : int {
    verifySession();
    $addId = $_SESSION['auth']->getAddressId($_SESSION['email']);
    if (!$_SESSION['auth']->authenticate($_SESSION['email'], $password)) {
        echo "<script type='text/javascript'>alert('Incorrect Password!');</script>";
        echo "<script type='text/javascript'>location.href = '../public/basket.php';</script>";
    } else if ($addId === -1) {
        echo "<script type='text/javascript'>alert('You need to set an address!');</script>";
        echo "<script type='text/javascript'>location.href = '../public/basket.php';</script>";
    } else if (isset($_SESSION['basket']) && !empty($_SESSION['basket'])) {
        $total = 0;
        foreach ($_SESSION['basket'] as $id => $amount) {
            $total += $_SESSION['films']->getFilmById($id)->getCost() * $amount;
        }
        /**Gets Current Date */
        $currDate = date('y') . '-' . date('m') . '-' . date('d');
        /** Updates Payment Table */
        $prepStm = $_SESSION['db']->getPreparedStatement('INSERT INTO fss_Payment (amount, paydate, shopid, ptid)  VALUES (:amount, :purchdate, 1, 2);');
        $prepStm->execute(['date' => $currDate, 'amount' => $total]);
        $payId = $_SESSION['db']->getLastId();
        /** Updates Online Payment Table */
        $prepStm = $_SESSION['db']->getPreparedStatement('INSERT INTO fss_OnlinePayment VALUES (:id, :account);');
        $prepStm->execute(['id' => $payId, 'account' => $_SESSION['auth']->getUserId($_SESSION['email'])]);
        foreach ($_SESSION['basket'] as $filmId => $amount) {
            /** Gets Current Film Price */
            $filmPrice = $_SESSION['films']->getFilmById($filmId)->getCost();
            $_SESSION['films']->takeStock($filmId, $amount);
            for ($x = 0; $x < $amount; $x++) {
                $prepStm = $_SESSION['db']->getPreparedStatement('INSERT INTO fss_FilmPurchase (payid, filmid, shopid, price) VALUES (:id, :film, 1, :price);');
                $prepStm->execute(['id' => $payId, 'film' => $filmId, 'price' => $filmPrice]);
                $fpId = $_SESSION['db']->getLastId();
                $prepStm = $_SESSION['db']->getPreparedStatement('INSERT INTO fss_OnlinePurchase VALUES (:id, :address);');
                $prepStm->execute(['id' => $fpId, 'address' => $addId]);
            }
        }
        unset($_SESSION['basket']);
        echo "<script type='text/javascript'>alert('Successfully Purchased!');</script>";
        echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";

    } else {
        echo "<script type='text/javascript'>alert('Your basket is empty!');</script>";
        echo "<script type='text/javascript'>location.href = '../public/basket.php';</script>";
    }

}


if (isset($_POST['method'], $_POST['id']) || isset($_POST['method'], $_POST['password'])) {
    if ($_POST['method'] === 'add') {
        addToBasket($_POST['id']);
    } else if ($_POST['method'] === 'remove') {
        removeFromBasket($_POST['id']);
    } else if ($_POST['method'] === 'purchase') {
        purchaseBasket($_POST['password']);
    } else {
        echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
    }
} else {
    echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
}