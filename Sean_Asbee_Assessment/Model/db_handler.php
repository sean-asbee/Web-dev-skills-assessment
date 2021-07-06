<?php
// Creates the link to the database that is used all over the project

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "library";

// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    print_r("No Connection Made");
    exit;
    die("Connection failed: " . $conn->connect_error);
}
