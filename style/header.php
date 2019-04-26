<!--  -->
<div id="header" class="header">
    <div class="login-status">
        <?php
        include '../controllers/SessionController.php';
        verifySession();
        if (isset($_SESSION['email'], $_SESSION['logged_in']) && $_SESSION['logged_in']) {
            echo 'You are logged in as ' . $_SESSION['email'];
        } else {
            echo 'You are not logged in';
        }

        ?>
    </div>
    <div class="account" style="float: right;">
        <form action="../controllers/ResetSession.php" method="post">
            <input type="hidden" name="method" value="reset">
            <input type="submit" value="DEV USE: RESET SESSION">
        </form>
        <?php
        echo '<form action="index.php"><input type="submit" value="Homepage"></form>';
        if (isset($_SESSION['email'], $_SESSION['logged_in']) && $_SESSION['logged_in']) {
            if (isset($_SESSION['basket']) && !empty($_SESSION['basket'])) {
                echo '<form action="basket.php"><input type="submit" value="View Basket"></form>';
            }
            echo '<form action="orders.php"><input type="submit" value="View Previous Orders"></form>';
            echo '<form action="../controllers/LogoutController.php"><input type="submit" value="Logout"></form>';
        } else {
            echo '<form action="login.php"><input type="submit" value="Login"></form>';
            echo '<form action="register.php"><input type="submit" value="Register"></form>';
        }
        ?>
    </div>
</div>