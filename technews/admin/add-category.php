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
                <h1 class="admin-heading">Add New Category</h1>
            </div>
            <div class="col-md-offset-3 col-md-6">
                <?php
                if (isset($_POST["save"])) {
                    $category = mysqli_real_escape_string($conn, $_POST["cat"]);
                    $query    = mysqli_real_escape_string($conn, $_POST["query"]);

                    // Check if category already exists
                    $check_category_sql    = "SELECT category_name FROM category WHERE category_name = '{$category}'";
                    ($check_category_result = mysqli_query($conn, $check_category_sql)) or die("Query failed");

                    if (mysqli_num_rows($check_category_result) > 0) {
                        echo "<p style='color:red;text-align:center;margin:10px 0;'>Category Name Already Exists</p>";
                    } else {
                        $insert_category_sql = "INSERT INTO category (category_name, post, query)
                                 VALUES ('{$category}', 0, '{$query}')";

                        if (mysqli_query($conn, $insert_category_sql)) {
                            header("Location: category.php");
                        }
                    }
                }
                ?>
                <form action="" method="POST" autocomplete="off">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" name="cat" class="form-control" placeholder="Category Name" required>
                    </div>
                    <div class="form-group">
                        <label>Query</label>
                        <input type="text" name="query" class="form-control" placeholder="query" required>
                    </div>
                    <input type="submit" name="save" class="btn btn-primary" value="Save" required />
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>