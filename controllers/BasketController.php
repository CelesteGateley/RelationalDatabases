<?php
/** File Created by u1755082 (Kieran Gateley) for module CIS2360 - Relational Databases and Web Integration */
include 'SessionController.php';

function isNotNull(array $vars) : bool {
    foreach ($vars as $var) { if ($var === '' || empty($var)) { return false; } }
    return true;
}

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

function purchaseBasket(string $password, string $cardNo, string $cardType, int $cardExpDay, int $cardExpMonth) {
    verifySession();
    $addId = $_SESSION['auth']->getAddressId($_SESSION['email']);
    if (!$_SESSION['auth']->authenticate($_SESSION['email'], $password)) {
        echo "<script type='text/javascript'>alert('Incorrect Password!');</script>";
        echo "<script type='text/javascript'>location.href = '../public/basket.php';</script>";
    } else if ($addId === -1) {
        echo "<script type='text/javascript'>alert('You need to set an address!');</script>";
        echo "<script type='text/javascript'>location.href = '../public/basket.php';</script>";
    } else if (($cardExpDay <= 31 && $cardExpDay >= 1) && ($cardExpMonth <= 12 && $cardExpMonth >= 1) && !preg_match('/\d{10}/',$cardNo)) {
            echo "<script type='text/javascript'>alert('Invalid Card Information!');</script>";
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
        $prepStm->execute(['purchdate' => $currDate, 'amount' => $total]);
        $payId = $_SESSION['db']->getLastId();
        /** Updates Online Payment Table */
        $prepStm = $_SESSION['db']->getPreparedStatement('INSERT INTO fss_OnlinePayment VALUES (:id, :account);');
        $prepStm->execute(['id' => $payId, 'account' => $_SESSION['auth']->getUserId($_SESSION['email'])]);
        /** Update Card Payment Table */
        $cardExp = $cardExpMonth . ':' . $cardExpDay;
        $prepStm = $_SESSION['db']->getPreparedStatement('INSERT INTO fss_CardPayment VALUES (:id, :cno, :ctype, :cexpr);');
        $prepStm->execute(['id' => $payId, 'cno' => $cardNo, 'ctype' => $cardType, 'cexpr' => $cardExp]);

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


if ((isset($_POST['method'], $_POST['id']) && isNotNull([$_POST['method'], $_POST['id']])) || (isset($_POST['method'], $_POST['password'], $_POST['card_info']) && isNotNull([$_POST['method'], $_POST['password'], $_POST['card_info']]))) {
    verifySession();
    if ($_POST['method'] === 'add') {
        addToBasket($_POST['id']);
    } else if ($_POST['method'] === 'remove') {
        removeFromBasket($_POST['id']);
    } else if ($_POST['method'] === 'purchase') {
        if (isset($_POST['use_previous']) && $_POST['use_previous'] === 'yes') {
            $cardInfo = $_SESSION['auth']->getCardInfo($_SESSION['email']);
            if (!empty($cardInfo)) {
                purchaseBasket($_POST['password'], $cardInfo['cno'], $cardInfo['ctype'], $cardInfo['expday'], $cardInfo['expmo']);
            } else {
                echo "<script type='text/javascript'>alert('You have no previous card info!');</script>";
                echo "<script type='text/javascript'>location.href = '../public/basket.php';</script>";
            }
        }
        if (isset($_POST['card_info']['cardNo'], $_POST['card_info']['cardType'], $_POST['card_info']['cardExp']['expDay'], $_POST['card_info']['cardExp']['expMonth']) && isNotNull([$_POST['card_info']['cardNo'], $_POST['card_info']['cardType'], $_POST['card_info']['cardExp']['expDay'], $_POST['card_info']['cardExp']['expMonth']])) {
            purchaseBasket($_POST['password'], $_POST['card_info']['cardNo'], $_POST['card_info']['cardType'], $_POST['card_info']['cardExp']['expDay'], $_POST['card_info']['cardExp']['expMonth']);
        } else {
            echo "<script type='text/javascript'>alert('You need to provide card information!');</script>";
            echo "<script type='text/javascript'>location.href = '../public/basket.php';</script>";
        }
    } else {
        echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
    }
} else {
    echo "<script type='text/javascript'>alert('All information must be provided!');</script>";
    echo "<script type='text/javascript'>location.href = '../public/basket.php';</script>";
}