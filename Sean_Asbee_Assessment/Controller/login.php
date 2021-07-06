<?php
// This is the controller for the login menu 
include 'includes.php';

// Bind parameters from new user
// Then create the user in the database
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passconfirm = $_POST['passwordconfirm'];

    if ($password !== $passconfirm) {

        header("location: ../View/index.php?error=passwordMismatch");
        exit();
    }

    //Located in 'includes.php'
    createUser($conn, $name, $username, $password);
}
// Bind parameters from returning user
// Then run login function 
elseif (isset($_POST['usernameLogin'])) {

    $username = $_POST['usernameLogin'];
    $password = $_POST['passwordLogin'];

    //Located in 'includes.php'
    loginUser($conn, $username, $password);
}
