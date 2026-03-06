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

if (isset($_POST["update"])) {
    $category_id   = mysqli_real_escape_string($conn, $_POST["cat_id"]);
    $category_name = mysqli_real_escape_string($conn, $_POST["cat_name"]);
    $query         = mysqli_real_escape_string($conn, $_POST["query"]);

    $update_category_sql = "UPDATE category SET category_name = '{$category_name}', query = '{$query}' WHERE category_id = {$category_id}";

    if (mysqli_query($conn, $update_category_sql)) {
        header("Location: category.php");
    }
}
?>
<div id="admin-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="admin-heading">Update Category</h1>
            </div>
            <div class="col-md-offset-3 col-md-6">
                <?php
                $cat_id = $_GET["id"];
                $fetch_category_sql    = "SELECT * FROM category WHERE category_id = {$cat_id}";
                ($fetch_category_result = mysqli_query($conn, $fetch_category_sql)) or die("Query failed");

                if (mysqli_num_rows($fetch_category_result) > 0) {
                    while ($category_row = mysqli_fetch_assoc($fetch_category_result)) {
                ?>
                        <form method="POST">
                            <div class="form-group">
                                <input type="hidden" name="cat_id" class="form-control" value="<?php echo $category_row["category_id"]; ?>">
                            </div>
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" name="cat_name" class="form-control" value="<?php echo $category_row["category_name"]; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Query</label>
                                <input type="text" name="query" class="form-control" value="<?php echo htmlspecialchars($category_row["query"]); ?>" required>
                            </div>
                            <input type="submit" name="update" class="btn btn-primary" value="Update" required />
                        </form>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>