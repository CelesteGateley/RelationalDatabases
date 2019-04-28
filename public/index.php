<!-- File Created by u1755082 (Kieran Gateley) for module CIS2360 - Relational Databases and Web Integration -->
<html lang="en">
<?php ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); ?>
<head>
    <title>Film store</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>
<?php include '../style/header.php'; ?>
<p>
<div style="display: block; text-align: center; color: red; width: 100%">Films highlighted in red are out of stock!</div>
<p>
<div class="film-table">
    <table class="product-table">
        <tr>
            <th>Film Name</th>
            <th>Description</th>
            <th>Rating</th>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { echo '<th>Stock</th>'; echo '<th>Add to Basket</th>'; } ?>
        </tr>
        <?php
            verifySession();
            foreach ($_SESSION['films']->getAllFilms() as $film) {
                $stock = $film->getStock();
                if (isset($_SESSION['basket'][$film->getId()])) {
                    $stock -= $_SESSION['basket'][$film->getId()];
                }
                if ($stock <= 0) { echo '<tr style="color: red;">';}
                else { echo '<tr>'; }
                echo '<td>' . $film->getName() . '</td>';
                echo '<td>' . $film->getDescription() . '</td>';
                echo '<td>' . $_SESSION['films']->getRatingKey()[$film->getRatingId()] . '</td>';
                if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
                    echo '<td>' . $stock . '</td>';
                    if ($stock <= 0) { echo '<td>N/A</td>'; }
                    else {
                        echo '<td><form method="post" action="../controllers/BasketController.php">
                               <input type="hidden" name="id" value="' . $film->getId() . '">
                               <input type="hidden" name="method" value="add">
                               <input type="submit" value="Add">
                               </form>
                          </td>';
                    }
                }
                echo '</tr>';
            }
        ?>
    </table>
</div>
<?php include_once '../style/footer.php'; ?>
</body>
</html>