<?php
// This is the page that displays the main book table to the user 
include 'header.php';
?>

<body>
    <div class="subheading">
        <h3>Book Listings</h3>
        <a href="../View/create.php" class="btn btn-success"><i class="bi bi-plus-square"></i> Add a Book</a>
    </div>
    <div class="bookTable">
        <table class="table table-striped" data-filter-control="true">
            <thead>
                <tr>
                    <th scope="col">
                        <a href="../View/welcome.php?query=ids">Book ID</a>
                    </th>
                    <th scope="col">Photo</th>
                    <th scope="col">
                        <a href="../View/welcome.php?query=titles">Title</a>
                    </th>
                    <th scope="col"><a href="../View/welcome.php?query=genres">Genre</th>
                    <th scope="col"><a href="../View/welcome.php?query=authors">Author</th>
                    <th scope="col"><a href="../View/welcome.php?query=dates">Year Published</th>
                    <th scope="col">Copies Available</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../Controller/init_table.php';
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>