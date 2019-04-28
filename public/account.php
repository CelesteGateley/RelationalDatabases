<html lang="en">
<head>
    <title>Film store</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>
<?php include '../style/header.php'; ?>
<?php include_once '../controllers/AccountModificationController.php'; $address = getCurrentAddress($_SESSION['email']); $info = getCurrentInfo($_SESSION['email']);?>
<?php
if (!(isset($_SESSION['verify-timeout']) && $_SESSION['verify-timeout'] > time())) {
    echo "<script type='text/javascript'>location.href = '../public/verify.php';</script>";
} ?>
<p>
<div style="display: inline-block;"> <!-- Set Address -->
<form action="../controllers/AccountModificationController.php" method="post">
    <br>Street:<br>
    <?php if (isset($address['street'])) { echo '<input type="text" name="street" value="'. $address['street'].'">';}
          else { echo '<input type="text" name="street">'; }?>
    <br>City:<br>
    <?php if (isset($address['city'])) { echo '<input type="text" name="city" value="'. $address['city'].'">';}
          else { echo '<input type="text" name="city">'; }?>
    <br>Postcode:<br>
    <?php if (isset($address['postcode'])) { echo '<input type="text" name="postcode" value="'. $address['postcode'].'">';}
          else { echo '<input type="text" name="postcode">'; }?>
    <input type="hidden" name="method" value="update_address"><br><br>
    <input type="submit" value="Change Address">
</form>
</div>
<div style="display: inline-block;"> <!-- Change Password -->
<form action="../controllers/AccountModificationController.php" method="post">
    <br>Old Password:<br>
    <input type="password" name="current_password">
    <br>New Password:<br>
    <input type="password" name="new_password">
    <br>Confirm Password:<br>
    <input type="password" name="confirm_password">
    <input type="hidden" name="method" value="update_password">
    <br><br>
    <input type="submit" value="Update Password">
</form>
</div>
<div style="display: inline-block;"> <!-- Change Name and Email -->
    <form action="../controllers/AccountModificationController.php" method="post">
        <br>Full Name:<br>
        <?php if (isset($info['name'])) { echo '<input type="text" name="name" value="'. $info['name'].'">';}
              else { echo '<input type="text" name="name">'; }?>
        <br>Email Address:<br>
        <?php if (isset($info['email'])) { echo '<input type="text" name="email" value="'. $info['email'].'">';}
              else { echo '<input type="text" name="email">'; }?>
        <br>Phone Number:<br>
        <?php if (isset($info['phone'])) { echo '<input type="text" name="phone" value="'. $info['phone'].'">';}
        else { echo '<input type="text" name="phone">'; }?>
        <br>Confirm Password:<br>
        <input type="password" name="confirm_password">
        <input type="hidden" name="method" value="update_details">
        <br><br>
        <input type="submit" value="Change Details">
    </form>
</div>
</body>
</html>
