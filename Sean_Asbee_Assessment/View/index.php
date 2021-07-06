<?php
// Author: Sean Asbee
// Last Updated: July 6, 2021

// I created a seperate file for the header and footer in my project so I
// didn't have to rewrite as much definition code
include 'header.php';
?>

<!-- This is the landing page / login page for my simple CRUD website assessment. I have created
a corporate facing website for updating the library. -->

<!-- This first page just defines the visual presentation of a simple login menu  -->

<div class="loginMenu">
    <div class="row">
        <div class="col login_box">
            <h4>Login</h4>
            <form class="login_form" action="../Controller/login.php" method="post">
                <div class="mb-3 ">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="usernameLogin" name="usernameLogin" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="passwordLogin" name="passwordLogin" required>
                </div>
                <input type="submit" value="Go">
            </form>
        </div>
        <div class="col newuser_box">
            <h4>New User</h4>
            <form action="../Controller/login.php" method="post">
                <div class=" mb-3 ">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3 ">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="passwordconfirm" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="passwordconfirm" name="passwordconfirm" required>
                </div>
                <input type="submit" value="Go">
            </form>
        </div>
    </div>
    <?php
    if (isset($_GET['error'])) {
        if ($_GET['error'] == "passwordMismatch") {
            echo "<h4>Passwords need to match!</h4>";
        } elseif ($_GET['error'] == "badcharacter") {
            echo '<h4 style="text-decoration:none;">Cannot contain: \ / < > \' " ; . =</h4>';
        }
    }
    if (isset($_GET['status'])) {
        if ($_GET['status'] == "usercreated") {
            echo "<h4>User added to database!</h4>";
        }
    }
    ?>
</div>

<?php
// I created a seperate file for the header and footer in my project so I
// didn't have to rewrite as much definition code
include 'footer.php';
?>