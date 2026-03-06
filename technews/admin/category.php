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
            <div class="col-md-10">
                <h1 class="admin-heading">All Categories</h1>
            </div>
            <div class="col-md-2">
                <a class="add-new" href="add-category.php">add category</a>
            </div>
            <div class="col-md-12">
                <?php
                $limit = 5;
                $page  = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
                $page = max(1, $page);
                $offset = ($page - 1) * $limit;

                $categories_sql = "SELECT * FROM category ORDER BY category_id DESC LIMIT {$offset}, {$limit}";
                ($categories_result = mysqli_query($conn, $categories_sql)) or die("Query failed");

                if (mysqli_num_rows($categories_result) > 0) {
                ?>
                    <table class="content-table">
                        <thead>
                            <th>S.No.</th>
                            <th>Category Name</th>
                            <th>No. of Posts</th>
                            <th>Query</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </thead>
                        <tbody>
                            <?php while ($category = mysqli_fetch_assoc($categories_result)) { ?>
                                <tr>
                                    <td class='id'><?php echo $category["category_id"]; ?></td>
                                    <td><?php echo $category["category_name"]; ?></td>
                                    <td><?php echo $category["post"]; ?></td>
                                    <td><?php echo $category["query"]; ?></td>
                                    <td class='edit'>
                                        <a href='update-category.php?id=<?php echo $category["category_id"]; ?>'>
                                            <i class='fa fa-edit'></i>
                                        </a>
                                    </td>
                                    <td class='delete'>
                                        <a href='delete-category.php?id=<?php echo $category["category_id"]; ?>'>
                                            <i class='fa fa-trash-o'></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <?php
                    // Pagination
                    $category_count_sql = "SELECT COUNT(*) as total FROM category";
                    ($category_count_result = mysqli_query($conn, $category_count_sql)) or die("Query Failed");

                    $category_count_row = mysqli_fetch_assoc($category_count_result);
                    if ((int)$category_count_row["total"] > 0) {
                        $total_records = (int)$category_count_row["total"];
                        $total_pages   = ceil($total_records / $limit);

                        echo "<ul class='pagination admin-pagination'>";

                        if ($page > 1) {
                            echo '<li><a href="category.php?page=' . ($page - 1) . '">Prev</a></li>';
                        }

                        $current_group = ceil($page / 3);
                        $start = ($current_group - 1) * 3 + 1;
                        $end   = min($start + 2, $total_pages);

                        for ($i = $start; $i <= $end; $i++) {
                            $active = ($i == $page) ? "active" : "";
                            echo '<li class="' . $active . '"><a href="category.php?page=' . $i . '">' . $i . "</a></li>";
                        }

                        if ($page < $total_pages) {
                            echo '<li><a href="category.php?page=' . ($page + 1) . '">Next</a></li>';
                        }

                        echo "</ul>";
                    }
                    ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>