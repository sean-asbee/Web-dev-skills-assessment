<?php
// create.php displays the form to add a new book to the database
// and pushes that form to the create controller.
include "header.php";
?>
<div class="subheading">
    <h3>Add New Book</h3>
</div>
<br>
<div class="bookTable">
    <form action="../Controller/create.php" method="post">
        <div class="row">
            <div class="col">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="col">
                <label for="genre" class="form-label">Genre</label>
                <input type="text" class="form-control" id="genre" name="genre">
            </div>
            <div class="col">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" id="author" name="author">
            </div>
            <div class="col">
                <label for="year" class="form-label">Year Published</label>
                <input type="text" class="form-control" id="year" name="year">
            </div>
            <div class="col">
                <label for="copies" class="form-label">Total Copies</label>
                <input type="text" class="form-control" id="copies" name="copies">
            </div>
            <div class="row">
                <div class="col">
                    <label for="photo_link" class="form-label">Photo Name (File Location: "/Sean_Asbee_Assessment/images/{image_name}")</label>
                    <input type="file" class="form-control" id="photo_link" name="photo_link">
                </div>
            </div>
        </div>
        <br>
        <input type="submit" value="     Finish     ">
    </form>
</div>

<?php
include "footer.php";
?>