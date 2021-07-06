<?php
// edit.php displays the form to add a new book to the database
// and pushes that form to the edit controller.
include "header.php";
?>
<div class="subheading">
    <h3>Edit Book Details</h3>
</div>
<br>
<div class="bookTable">
    <form action="../Controller/edit.php" method="post">
        <div class="row">
            <?php
            include "../Controller/includes.php";

            if (isset($_GET["id"])) {
                $id = $_GET["id"];

                writeEditScreen(fetchID($conn, $id));
            } else {
                header("location: ../View/welcome.php?error=missingid");
            }

            ?>
        </div>
        <br>
        <input type="submit" value="     Finish     ">

        <input type="reset" value="     Reset     ">
    </form>
</div>

<?php
include "footer.php";
?>