<?php
// delete.php displays the form to add a new book to the database
// and pushes that form to the delete controller.
include "header.php";
?>
<div class="subheading">
    <h3>Edit Book Details</h3>
</div>
<br>
<div class="bookTable">
    <form action="../Controller/delete.php" method="post">
        <div class="row">
            <?php
            include "../Controller/includes.php";

            if (isset($_GET["id"])) {
                $id = $_GET["id"];

                writeDeleteScreen(fetchID($conn, $id));
            } else {
                header("location: ../View/welcome.php?error=missingid");
            }

            ?>

        </div>
        <br>
        <p>
            Are you sure you wish to delete this entry?
        </p>
        <p>
            If not, simply press the back button in your browser.
        </p>
        <br>
        <input type="submit" value="     Yes     ">

    </form>
</div>

<?php
include "footer.php";
?>