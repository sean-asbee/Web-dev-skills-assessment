<?php
//Got here from ../View/delete.php
include "includes.php";

// Bind parameters from post if they are properly recieved
if (isset($_POST["book_id"])) {
    $book_id = $_POST["book_id"];
}

// Execute include function
deleteBookEntry($conn, $book_id);
