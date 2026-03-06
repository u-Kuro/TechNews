<?php
session_status() === PHP_SESSION_ACTIVE || session_start();
include "../config.php";

if (!isset($_SESSION["username"])) {
    header("Location: ../login.php");
    exit();
} elseif ($_SESSION["user_role"] == 0) {
    header("Location: ../user/home.php");
    exit();
}

include "header.php";
?>
<div id="admin-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="admin-heading">Add New Post</h1>
            </div>
            <div class="col-md-offset-3 col-md-6">
                <form action="save-post.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="post_title">Title</label>
                        <input type="text" name="post_title" class="form-control" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" name="author" class="form-control" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="postdesc">Description</label>
                        <textarea name="postdesc" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="datetime">Date</label>
                        <input type="datetime-local" name="datetime" class="form-control" id="datetime" required>
                    </div>
                    <div class="form-group">
                        <label for="categoryid">Category</label>
                        <select name="categoryid" class="form-control" required>
                            <option disabled selected value=""> Select Category</option>
                            <?php
                            $categories_sql    = "SELECT * FROM category";
                            ($categories_result = mysqli_query($conn, $categories_sql)) or die("Query Failed");

                            if (mysqli_num_rows($categories_result) > 0) {
                                while ($category_row = mysqli_fetch_assoc($categories_result)) {
                                    echo "<option value='{$category_row["category_id"]}' required>{$category_row["category_name"]}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="imageUrl">Image Url</label>
                        <input type="text" name="imageUrl" class="form-control" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="postUrl">Post Url</label>
                        <input type="text" name="postUrl" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="postcontent">Content</label>
                        <textarea name="postcontent" class="form-control" required rows="5"></textarea>
                    </div>
                    <input type="submit" name="submit" class="btn btn-primary" value="Save" required />
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>