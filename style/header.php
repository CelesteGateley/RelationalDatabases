<!--  -->
<div id="header" class="header">
    <div class="login-status">
        /** @noinspection UnknownInspectionInspection */<?php
        include '../controllers/SessionController.php';
        setupSession();
        if (isset($_SESSION['email'], $_SESSION['logged_in']) && $_SESSION['logged_in']) {
            echo 'You are logged in as ' . $_SESSION['email'];
        } else {
            echo 'You are not logged in';
        }

        ?>
    </div>
    <div class="account" style="float: right;">
        <?php
        echo '<form action="index.php"><input type="submit" value="Homepage"></form>';
        if (isset($_SESSION['email'], $_SESSION['logged_in']) && $_SESSION['logged_in']) {
            if (isset($_SESSION['basket']) && !empty($_SESSION['basket'])) {
                echo '<form action="basket.php"><input type="submit" value="View Basket"></form>';
            }
            echo '<form action="../controllers/LogoutController.php"><input type="submit" value="Logout"></form>';
        } else {
            echo '<form action="login.php"><input type="submit" value="Login"></form>';
            echo '<form action="register.php"><input type="submit" value="Register"></form>';
        }
        ?>
    </div>
</div>