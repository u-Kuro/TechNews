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
                $sql     = "SELECT post.post_id, post.title, post.author, post.description,
                                   category.category_name, post.post_date, post.post_img,
                                   post.category, post.content, post.post_url
                            FROM post
                            LEFT JOIN category ON post.category = category.category_id
                            WHERE post_id = {$post_id}";

                ($result = mysqli_query($conn, $sql)) or die("Query failed");

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $html_date = date("Y-m-d\TH:i", strtotime($row["post_date"]));
                ?>
                        <form action="save-update-post.php" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <div class="form-group">
                                <input type="hidden" name="post_id" class="form-control" value="<?php echo $row["post_id"]; ?>">
                            </div>
                            <div class="form-group">
                                <label for="post_title">Title</label>
                                <input type="text" name="post_title" class="form-control" value="<?php echo $row["title"]; ?>">
                            </div>
                            <div class="form-group">
                                <label for="post_author">Author</label>
                                <input type="text" name="post_author" class="form-control" value="<?php echo $row["author"]; ?>">
                            </div>
                            <div class="form-group">
                                <label for="postdesc">Description</label>
                                <textarea name="postdesc" class="form-control" required rows="5"><?php echo $row["description"]; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="datetime">Date</label>
                                <input type="datetime-local" name="datetime" class="form-control" value="<?php echo $html_date; ?>">
                            </div>
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select class="form-control" name="category">
                                    <?php
                                    $sql1    = "SELECT * FROM category";
                                    ($result1 = mysqli_query($conn, $sql1)) or die("Query Failed");

                                    if (mysqli_num_rows($result1) > 0) {
                                        while ($row1 = mysqli_fetch_assoc($result1)) {
                                            $selected = ($row["category"] == $row1["category_id"]) ? "selected" : "";
                                            echo "<option {$selected} value='{$row1["category_id"]}'>{$row1["category_name"]}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <input type="hidden" name="old_category" value="<?php echo $row["category"]; ?>">
                            </div>
                            <div class="form-group">
                                <label for="imageUrl">Image Url</label>
                                <input type="text" name="imageUrl" class="form-control" value="<?php echo htmlspecialchars($row["post_img"]); ?>">
                            </div>
                            <div class="form-group">
                                <label for="postUrl">Post Url</label>
                                <input type="text" name="postUrl" class="form-control" value="<?php echo $row["post_url"]; ?>">
                            </div>
                            <div class="form-group">
                                <label for="postcontent">Content</label>
                                <textarea name="postcontent" class="form-control" required rows="5"><?php echo $row["content"]; ?></textarea>
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