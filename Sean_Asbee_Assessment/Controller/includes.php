<?php
//This is the main backend of the project that handles a lot of database interactions

//This include makes a connection to the database
include '../Model/db_handler.php';

//Adds a new user to the users table
function createUser($conn, $name, $username, $password)
{

    // Run the user's entry through a quick regex check for any suspecious characters
    $pattern = "/\\|\/|\<|\>|\'|\"|\;|\.|\=/i";

    if (preg_match($pattern, $name) > 0) {
        header("location: ../View/index.php?error=badcharacter");
        exit();
    } elseif (preg_match($pattern, $username) > 0) {
        header("location: ../View/index.php?error=badcharacter");
        exit();
    } elseif (preg_match($pattern, $password) > 0) {
        header("location: ../View/index.php?error=badcharacter");
        exit();
    }

    //Hash the user's password for storage
    $hashedPass = password_hash($password, PASSWORD_DEFAULT);

    //create SQL query without sending info right away
    $sql = "INSERT INTO users (name, username, password) VALUES (?, ?, ?);";

    //initialize a new prepared statement
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../View/index.php?error=stmtfailed");
        exit();
    }

    //This binds the data to the question marks in the SQL query above
    mysqli_stmt_bind_param($stmt, "sss", $name, $username, $hashedPass);

    //finally execute the query
    mysqli_stmt_execute($stmt);

    //Close the mysql connection
    mysqli_stmt_close($stmt);

    header("location: ../View/index.php?status=usercreated");
    exit();
}

//Logs the user into the system
function loginUser($conn, $username, $password)
{
    //Check the database to see if the username
    //that has been submitted is registered in the system
    $userExists = usernameExists($conn, $username);

    if ($username === false) {
        header("location: ../View/index.php?error=wrongusername");
        exit();
    }

    //Retrieve hashed password from the database
    $hashedPass = $userExists["password"];

    //Verify the hashed password against the user's input
    //and start the session if it is correct
    if (password_verify($password, $hashedPass)) {
        session_start();
        $_SESSION["name"] = $userExists["name"];
        $_SESSION["username"] = $userExists["username"];
        header("location: ../View/welcome.php");
        exit();
    } else {
        header("location: ../View/index.php?error=wrongpassword");
        exit();
    }
}

//Verifies the existence of the user on the database
function usernameExists($conn, $username)
{
    //create SQL query without sending info right away
    $sql = "SELECT * FROM users WHERE username=?;";

    //initialize a new prepared statement
    $stmt = mysqli_stmt_init($conn);

    //Check the statement connection before binding
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../View/index.php?error=stmtfailed");
        exit();
    }

    //This binds the data to the question mark in the SQL query 
    mysqli_stmt_bind_param($stmt, "s", $username);

    //finally execute the query
    mysqli_stmt_execute($stmt);

    //Return the result
    $resultData = mysqli_stmt_get_result($stmt);

    //Fetch the data with an associative array
    //Create a variable and check the data at the same time
    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    //Close the mysql connection
    mysqli_stmt_close($stmt);
}

//Used in init_table to run different SQL commands through the table for filtering
function pullBookData($conn, $sql)
{
    //initialize a new prepared statement
    $stmt = mysqli_stmt_init($conn);

    //Check the statement connection before binding
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../View/welcome.php?error=stmtfailed");
        exit();
    }

    //finally execute the query
    mysqli_stmt_execute($stmt);

    //Return the result
    $resultData = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($resultData);

    //Close the mysql connection
    mysqli_stmt_close($stmt);
}

//This writes out the rows of the book table each time it is called
function writeRows($bookData)
{

    // $bookData is an array of arrays here, so I loop through the data with a while loop
    // on the first value, then select which piece of data to place with the second value
    $x = 0;

    while (isset($bookData[$x][0])) {
        echo '<tr class="bookRow">
                    <th scope="row">' . $bookData[$x][0] . '</th>
                    <td><img src="../images/' . $bookData[$x][6] . '" alt="' . $bookData[$x][6] . '"style="width:100px;height:150px;"></td>
                    <td>' . $bookData[$x][1] . '</td>
                    <td>' . $bookData[$x][2] . '</td>
                    <td>' . $bookData[$x][3] . '</td>
                    <td>' . $bookData[$x][4] . '</td>
                    <td>' . $bookData[$x][5] . '</td>
                    <td>
                    <a href="../View/edit.php?id=' . $bookData[$x][0] . '"> 
                    <i class="bi bi-pencil-square"></i>
                    </a>
                    <br>
                    <br>
                    <a href="../View/delete.php?id=' . $bookData[$x][0] . '">
                    <i class="bi bi-x-square"></i>
                    </a>
                    </td>
                </tr>';
        $x = $x + 1;
    }
}

// Retrieves book data and returns it as an associative array
function fetchID($conn, $id)
{
    //create SQL query without sending info right away
    $sql = "SELECT * FROM books WHERE book_id=?;";

    //initialize a new prepared statement
    $stmt = mysqli_stmt_init($conn);

    //Check the statement connection before binding
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../View/welcome.php?error=stmtfailed");
        exit();
    }

    //This binds the data to the question mark in the SQL query 
    mysqli_stmt_bind_param($stmt, "s", $id);

    //finally execute the query
    mysqli_stmt_execute($stmt);

    //Return the result
    $resultData = mysqli_stmt_get_result($stmt);

    //Fetch the data with an associative array
    //Create a variable and check the data at the same time
    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    //Close the mysql connection
    mysqli_stmt_close($stmt);
}

// This writes the edit screen and fills in the appropriate information
// from the passed $book which is an associative array
function writeEditScreen($book)
{
    echo '<div class="col">
            <label for="book_id" class="form-label">Book ID</label>
            <input type="text" class="form-control" id="book_id" name="book_id" value="' . $book["book_id"] . '" readonly>
        </div>
        <div class="col">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="' . $book["title"] . '">
        </div>
        <div class="col">
            <label for="genre" class="form-label">Genre</label>
            <input type="text" class="form-control" id="genre" name="genre" value="' . $book["genre"] . '">
        </div>
        <div class="col">
            <label for="author" class="form-label">Author</label>
            <input type="text" class="form-control" id="author" name="author" value="' . $book["author"] . '">
        </div>
        <div class="col">
            <label for="year" class="form-label">Year Published</label>
            <input type="text" class="form-control" id="year" name="year" value="' . $book["year"] . '">
        </div>
        <div class="col">
            <label for="copies" class="form-label">Total Copies</label>
            <input type="text" class="form-control" id="copies" name="copies" value="' . $book["copies"] . '">
        </div>
        <div class="row>
            <div class="col">
                <label for="photo_link" class="form-label">Photo Name (File Location: "/Sean_Asbee_Assessment/images/{image_name}")</label>
                <input type="text" class="form-control" id="photo_link" name="photo_link" value="' . $book["photo_link"] . '">
            </div>
        </div>';
}

//Updates the book's data after a user is done changing it
function editBookEntry($conn, $title, $genre, $author, $year, $copies, $photo_link, $book_id)
{
    // Run the user's entry through a quick regex check for any suspecious characters
    $pattern = "/\\|\/|\<|\>|\"|\;|\.|\=/i";

    if (preg_match($pattern, $title) > 0) {
        header("location: ../View/edit.php?id=" . $book_id . "&error=stmtfailed");
        exit();
    } elseif (preg_match($pattern, $genre) > 0) {
        header("location: ../View/edit.php?id=" . $book_id . "&error=stmtfailed");
        exit();
    } elseif (preg_match($pattern, $author) > 0) {
        header("location: ../View/edit.php?id=" . $book_id . "&error=stmtfailed");
        exit();
    } elseif (preg_match($pattern, $year) > 0) {
        header("location: ../View/edit.php?id=" . $book_id . "&error=stmtfailed");
        exit();
    } elseif (preg_match($pattern, $copies) > 0) {
        header("location: ../View/edit.php?id=" . $book_id . "&error=stmtfailed");
        exit();
    } elseif (preg_match("/\\|\/|\<|\>|\'|\"|\;|\=/i", $photo_link) > 0) {
        header("location: ../View/edit.php?id=" . $book_id . "&error=stmtfailed");
        exit();
    }

    //create SQL query without sending info right away
    $sql = "UPDATE books SET title=?, genre=?, author=?, year=?, copies=?, photo_link=? WHERE book_id=?;";

    //initialize a new prepared statement
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../View/edit.php?id=" . $book_id . "&error=stmtfailed");
        exit();
    }

    //This binds the data to the question marks in the SQL query above
    mysqli_stmt_bind_param($stmt, "sssiisi", $title, $genre, $author, $year, $copies, $photo_link, $book_id);

    //finally execute the query
    mysqli_stmt_execute($stmt);

    //Close the mysql connection
    mysqli_stmt_close($stmt);

    header("location: ../View/welcome.php?status=updatesuccess");
    exit();
}

//Adds a new book to the data base after filling the data in
function addBookEntry($conn, $title, $genre, $author, $year, $copies, $photo_link)
{
    // Run the user's entry through a quick regex check for any suspecious characters
    $pattern = "/\\|\/|\<|\>|\"|\;|\.|\=/i";

    if (preg_match($pattern, $title) > 0) {
        header("location: ../View/create.php?error=stmtfailed");
        exit();
    } elseif (preg_match($pattern, $genre) > 0) {
        header("location: ../View/create.php?error=stmtfailed");
        exit();
    } elseif (preg_match($pattern, $author) > 0) {
        header("location: ../View/create.php?error=stmtfailed");
        exit();
    } elseif (preg_match($pattern, $year) > 0) {
        header("location: ../View/create.php?error=stmtfailed");
        exit();
    } elseif (preg_match($pattern, $copies) > 0) {
        header("location: ../View/create.php?error=stmtfailed");
        exit();
    } elseif (preg_match("/\\|\/|\<|\>|\'|\"|\;|\=/i", $photo_link) > 0) {
        header("location: ../View/create.php?error=stmtfailed");
        exit();
    }

    //create SQL query without sending info right away
    $sql = "INSERT INTO books (title, genre, author, year, copies, photo_link) VALUES (?, ?, ?, ?, ?, ?);";

    //initialize a new prepared statement
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../View/create.php?error=stmtfailed");
        exit();
    }

    //This binds the data to the question marks in the SQL query above
    mysqli_stmt_bind_param($stmt, "sssiis", $title, $genre, $author, $year, $copies, $photo_link);

    //finally execute the query
    mysqli_stmt_execute($stmt);

    //Close the mysql connection
    mysqli_stmt_close($stmt);

    header("location: ../View/welcome.php?status=addsuccess");
    exit();
}

// This writes the delete screen and fills in the appropriate information
// from the passed $book which is an associative array
function writeDeleteScreen($book)
{

    echo '<div class="col">
            <label for="book_id" class="form-label">Book ID</label>
            <input type="text" class="form-control" id="book_id" name="book_id" value="' . $book["book_id"] . '" readonly>
        </div>
        <div class="col">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="' . $book["title"] . '" readonly>
        </div>
        <div class="col">
            <label for="genre" class="form-label">Genre</label>
            <input type="text" class="form-control" id="genre" name="genre" value="' . $book["genre"] . '" readonly>
        </div>
        <div class="col">
            <label for="author" class="form-label">Author</label>
            <input type="text" class="form-control" id="author" name="author" value="' . $book["author"] . '" readonly>
        </div>
        <div class="col">
            <label for="year" class="form-label">Year Published</label>
            <input type="text" class="form-control" id="year" name="year" value="' . $book["year"] . '" readonly>
        </div>
        <div class="col">
            <label for="copies" class="form-label">Total Copies</label>
            <input type="text" class="form-control" id="copies" name="copies" value="' . $book["copies"] . '" readonly>
        </div>
        <div class="row">
            <div class="col">
                <label for="photo_link" class="form-label">Photo Name (File Location: "/Sean_Asbee_Assessment/images/{image_name}")</label>
                <input type="text" class="form-control" id="photo_link" name="photo_link" value="' . $book["photo_link"] . '" readonly>
            </div>
        </div>';
}

//Delet's a book from the system based on its book_id
function deleteBookEntry($conn, $book_id)
{
    //create SQL query without sending info right away
    $sql = "DELETE FROM books WHERE book_id=?;";

    //initialize a new prepared statement
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../View/delete.php?error=stmtfailed");
        exit();
    }

    //This binds the data to the question marks in the SQL query above
    mysqli_stmt_bind_param($stmt, "i", $book_id);

    //finally execute the query
    mysqli_stmt_execute($stmt);

    //Close the mysql connection
    mysqli_stmt_close($stmt);

    header("location: ../View/welcome.php?status=deletesuccess");
    exit();
}
