<?php
// This handles the different filters on the data
// then it runs the SQL command into the includes function
include 'includes.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    if ($query == 'ids') {
        $sql = "SELECT * FROM books ORDER BY  `books`.`book_id` ASC;";
    } elseif ($query == 'titles') {
        $sql = "SELECT * FROM books ORDER BY  `books`.`title` ASC;";
    } elseif ($query == 'genres') {
        $sql = "SELECT * FROM books ORDER BY  `books`.`genre` ASC;";
    } elseif ($query == 'authors') {
        $sql = "SELECT * FROM books ORDER BY  `books`.`author` ASC;";
    } elseif ($query == 'dates') {
        $sql = "SELECT * FROM books ORDER BY  `books`.`year` ASC;";
    }
} else {
    //create SQL query without sending info right away
    $sql = "SELECT * FROM books;";
}

writeRows(pullBookData($conn, $sql));
