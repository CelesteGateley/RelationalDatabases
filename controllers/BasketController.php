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

if (isset($_POST['method'], $_POST['id'])) {
    if ($_POST['method'] === 'add') {
        addToBasket($_POST['id']);
    } else if ($_POST['method'] === 'remove') {
        removeFromBasket($_POST['id']);
    } else {
        echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
    }
} else {
    echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
}