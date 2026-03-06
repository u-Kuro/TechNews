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
                <h1 class="admin-heading">All Posts</h1>
            </div>
            <div class="col-md-2">
                <a class="add-new" href="add-post.php">add post</a>
            </div>
            <div class="col-md-12">
                <?php
                $limit  = 10;
                $page   = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
                $page = max(1, $page);
                $offset = ($page - 1) * $limit;

                if ($_SESSION["user_role"] == 1) {
                    $posts_sql = "SELECT post.post_id, post.title, category.category_name,
                                   post.post_date, post.author, post.category
                            FROM post
                            LEFT JOIN category ON post.category = category.category_id
                            ORDER BY post_date DESC
                            LIMIT {$offset}, {$limit}";
                }

                ($posts_result = mysqli_query($conn, $posts_sql)) or die("Query failed");

                if (mysqli_num_rows($posts_result) > 0) {
                ?>
                    <table class="content-table">
                        <thead>
                            <th>S.No.</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Author</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </thead>
                        <tbody>
                            <?php
                            $serial_number = $offset + 1;
                            while ($post = mysqli_fetch_assoc($posts_result)) {
                                $post_date = DateTime::createFromFormat("Y-m-d H:i:s", $post["post_date"])->format("M d, Y");
                            ?>
                                <tr>
                                    <td class='id'><?php echo $serial_number; ?></td>
                                    <td><?php echo $post["title"]; ?></td>
                                    <td><?php echo $post["category_name"]; ?></td>
                                    <td><?php echo $post_date; ?></td>
                                    <td><?php echo $post["author"]; ?></td>
                                    <td class='edit'>
                                        <a href='update-post.php?id=<?php echo $post["post_id"]; ?>'>
                                            <i class='fa fa-edit'></i>
                                        </a>
                                    </td>
                                    <td class='delete'>
                                        <a href='delete-post.php?id=<?php echo $post["post_id"]; ?>&catid=<?php echo $post["category"]; ?>'>
                                            <i class='fa fa-trash-o'></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php
                                $serial_number++;
                            }
                            ?>
                        </tbody>
                    </table>

                <?php
                    // Pagination
                    if ($_SESSION["user_role"] == 1) {
                        $post_count_sql = "SELECT COUNT(*) as total FROM post";
                    }

                    ($post_count_result = mysqli_query($conn, $post_count_sql)) or die("Query Failed");

                    $post_count_row = mysqli_fetch_assoc($post_count_result);
                    if ((int)$post_count_row["total"] > 0) {
                        $total_records = (int)$post_count_row["total"];
                        $total_pages   = ceil($total_records / $limit);

                        echo "<ul class='pagination admin-pagination'>";

                        if ($page > 1) {
                            echo '<li><a href="post.php?page=' . ($page - 1) . '">Prev</a></li>';
                        }

                        $current_group = ceil($page / 3);
                        $start = ($current_group - 1) * 3 + 1;
                        $end   = min($start + 2, $total_pages);

                        for ($i = $start; $i <= $end; $i++) {
                            $active = ($i == $page) ? "active" : "";
                            echo '<li class="' . $active . '"><a href="post.php?page=' . $i . '">' . $i . "</a></li>";
                        }

                        if ($page < $total_pages) {
                            echo '<li><a href="post.php?page=' . ($page + 1) . '">Next</a></li>';
                        }

                        echo "</ul>";
                    }
                } ?>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>