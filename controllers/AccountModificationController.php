<?php
/** File Created by u1755082 (Kieran Gateley) for module CIS2360 - Relational Databases and Web Integration */
include_once 'SessionController.php';
include_once 'LogoutController.php';
function verify(string $password) : bool {
    verifySession();
    if (isset($_SESSION['logged_in'], $_SESSION['email']) && $_SESSION['logged_in'] && $_SESSION['auth']->authenticate($_SESSION['email'], $password)) {
        $_SESSION['verify-timeout'] = time() + 120;
        return true;
    }
    return false;
}

function updateDetails(string $email, string $name, string $phone) {
    verifySession();
    return $_SESSION['auth']->updateInfo($_SESSION['auth']->getUserId($email), $name, $phone, $email);
}

function updateAddress(string $email, string $street, string $city, string $postcode) {
    verifySession();
    $addId = $_SESSION['auth']->getAddressId($email);
    if ($addId === -1) {
        $_SESSION['auth']->setAddress($email, filter_var($street, FILTER_SANITIZE_ADD_SLASHES), filter_var($city, FILTER_SANITIZE_ADD_SLASHES), filter_var($postcode, FILTER_SANITIZE_ADD_SLASHES));
    } else {
        $_SESSION['auth']->updateAddress($addId, filter_var($street, FILTER_SANITIZE_ADD_SLASHES), filter_var($city, FILTER_SANITIZE_ADD_SLASHES), filter_var($postcode, FILTER_SANITIZE_ADD_SLASHES));
    }
}

function getCurrentInfo(string $email) {
    verifySession();
    $name = $_SESSION['auth']->getName($email);
    $phone = $_SESSION['auth']->getPhoneNumber($email);
    return ['email' => $email, 'name' => $name, 'phone' => $phone];

}

function getCurrentAddress(string $email) {
    verifySession();
    $addId = $_SESSION['auth']->getAddressId($email);
    if ($addId !== -1) {
        $adRes = $_SESSION['db']->query('SELECT addstreet, addcity, addpostcode FROM fss_Address WHERE addid = ' . $addId . ';');
        $address = array();
        $address['street'] = $adRes[0][0];
        $address['city'] = $adRes[0][1];
        $address['postcode'] = $adRes[0][2];
        return $address;
    }
    return array();
}

function updatePassword(string $email, string $oldPass, string $newPass, string $confPass) {
    verifySession();
    if ($newPass === $confPass) {
        return $_SESSION['auth']->updatePassword($_SESSION['auth']->getUserId($email), $oldPass, $newPass);
    }
    return false;
}

if (isset($_POST['method'])) {
    verifySession();
    if ($_POST['method'] === 'verify') {
        $verification = verify($_POST['value']);
        if ($verification) {
            echo "<script type='text/javascript'>location.href = '../public/account.php';</script>";
        }
        echo "<script type='text/javascript'>alert('Incorrect Password!');</script>";
        echo "<script type='text/javascript'>location.href = '../public/verify.php';</script>";
    } else if ($_POST['method'] === 'update_password' && isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
        $conf = updatePassword($_SESSION['email'], $_POST['current_password'], $_POST['new_password'], $_POST['confirm_password']);
        if ($conf) {
            $emailTemp = $_SESSION['email'];
            echo "<script type='text/javascript'>alert('Password Updated Successfully! Please log back in!');</script>";
            logout();
            $_SESSION['email'] = $emailTemp;
            echo "<script type='text/javascript'>location.href = '../public/index.php';</script>";
        }
        echo "<script type='text/javascript'>alert('Error!');</script>";
    } else if ($_POST['method'] === 'update_address' && isset($_POST['street'], $_POST['city'], $_POST['postcode'])) {
        updateAddress($_SESSION['email'], $_POST['street'], $_POST['city'], $_POST['postcode']);
        echo "<script type='text/javascript'>alert('Address Updated!');</script>";
        echo "<script type='text/javascript'>location.href = '../public/account.php';</script>";
    } else if ($_POST['method'] === 'update_details' && isset($_POST['name'], $_POST['email'], $_POST['phone']) && $_POST['name'] !== '' && $_POST['email'] !== '' && $_POST['phone'] !== '') {
        $val = updateDetails($_POST['email'], $_POST['name'], $_POST['phone']);
        if ($val === -1) {
            echo "<script type='text/javascript'>alert('Invalid Email Address!');</script>";
            echo "<script type='text/javascript'>location.href = '../public/account.php';</script>";
        } else if ($val === -2) {
            echo "<script type='text/javascript'>alert('Invalid Phone Number!');</script>";
            echo "<script type='text/javascript'>location.href = '../public/account.php';</script>";
        } else {
            echo "<script type='text/javascript'>alert('Details Updated Successfully!');</script>";
            echo "<script type='text/javascript'>location.href = '../public/account.php';</script>";
        }
    }
}