<div class="film-table" style="overflow: scroll;">
    <table class="product-table">
        <tr>
            <th>Film Name</th>
            <th>Description</th>
            <th>Rating</th>
            <th>Cost</th>
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
                echo '<td>£' . $film->getCost() . '</td>';
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