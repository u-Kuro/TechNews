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
                <h1 class="admin-heading">Update Post</h1>
            </div>
            <div class="col-md-offset-3 col-md-6">
                <?php
                $post_id = $_GET["id"];
                $fetch_post_sql     = "SELECT post.post_id, post.title, post.author, post.description,
                                   category.category_name, post.post_date, post.post_img,
                                   post.category, post.content, post.post_url
                            FROM post
                            LEFT JOIN category ON post.category = category.category_id
                            WHERE post_id = {$post_id}";

                ($fetch_post_result = mysqli_query($conn, $fetch_post_sql)) or die("Query failed");

                if (mysqli_num_rows($fetch_post_result) > 0) {
                    while ($post_row = mysqli_fetch_assoc($fetch_post_result)) {
                        $html_date = date("Y-m-d\TH:i", strtotime($post_row["post_date"]));
                ?>
                        <form action="save-update-post.php" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <div class="form-group">
                                <input type="hidden" name="post_id" class="form-control" value="<?php echo $post_row["post_id"]; ?>">
                            </div>
                            <div class="form-group">
                                <label for="post_title">Title</label>
                                <input type="text" name="post_title" class="form-control" value="<?php echo $post_row["title"]; ?>">
                            </div>
                            <div class="form-group">
                                <label for="post_author">Author</label>
                                <input type="text" name="post_author" class="form-control" value="<?php echo $post_row["author"]; ?>">
                            </div>
                            <div class="form-group">
                                <label for="postdesc">Description</label>
                                <textarea name="postdesc" class="form-control" required rows="5"><?php echo $post_row["description"]; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="datetime">Date</label>
                                <input type="datetime-local" name="datetime" class="form-control" value="<?php echo $html_date; ?>">
                            </div>
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select class="form-control" name="category">
                                    <?php
                                    $categories_sql    = "SELECT * FROM category";
                                    ($categories_result = mysqli_query($conn, $categories_sql)) or die("Query Failed");

                                    if (mysqli_num_rows($categories_result) > 0) {
                                        while ($category_option_row = mysqli_fetch_assoc($categories_result)) {
                                            $selected = ($post_row["category"] == $category_option_row["category_id"]) ? "selected" : "";
                                            echo "<option {$selected} value='{$category_option_row["category_id"]}'>{$category_option_row["category_name"]}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <input type="hidden" name="old_category" value="<?php echo $post_row["category"]; ?>">
                            </div>
                            <div class="form-group">
                                <label for="imageUrl">Image Url</label>
                                <input type="text" name="imageUrl" class="form-control" value="<?php echo htmlspecialchars($post_row["post_img"]); ?>">
                            </div>
                            <div class="form-group">
                                <label for="postUrl">Post Url</label>
                                <input type="text" name="postUrl" class="form-control" value="<?php echo $post_row["post_url"]; ?>">
                            </div>
                            <div class="form-group">
                                <label for="postcontent">Content</label>
                                <textarea name="postcontent" class="form-control" required rows="5"><?php echo $post_row["content"]; ?></textarea>
                            </div>
                            <input type="submit" name="submit" class="btn btn-primary" value="Update" />
                        </form>
                <?php
                    }
                } else {
                    echo "Result Not Found";
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>