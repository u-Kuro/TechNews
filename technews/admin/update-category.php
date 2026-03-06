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

    $sql2 = "UPDATE category SET category_name = '{$category_name}', query = '{$query}' WHERE category_id = {$category_id}";

    if (mysqli_query($conn, $sql2)) {
        header("Location: category.php");
    }
}
?>
<div id="admin-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="adin-heading">Update Category</h1>
            </div>
            <div class="col-md-offset-3 col-md-6">
                <?php
                $cat_id = $_GET["id"];
                $sql    = "SELECT * FROM category WHERE category_id = {$cat_id}";
                ($result = mysqli_query($conn, $sql)) or die("Query failed");

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <form method="POST">
                            <div class="form-group">
                                <input type="hidden" name="cat_id" class="form-control" value="<?php echo $row["category_id"]; ?>">
                            </div>
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" name="cat_name" class="form-control" value="<?php echo $row["category_name"]; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Query</label>
                                <input type="text" name="query" class="form-control" value="<?php echo htmlspecialchars($row["query"]); ?>" required>
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