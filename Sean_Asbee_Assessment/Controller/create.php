<?php
//Got here from ../View/create.php
include "includes.php";

// Bind parameters from post if they are properly recieved
if (isset($_POST["title"])) {
    $title = $_POST["title"];
    $genre = $_POST["genre"];
    $author = $_POST["author"];
    $year = $_POST["year"];
    $copies = $_POST["copies"];
    $photo_link = $_POST["photo_link"];
}

// Execute include function
addBookEntry($conn, $title, $genre, $author, $year, $copies, $photo_link);
