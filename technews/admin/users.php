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
                <h1 class="admin-heading">All Users</h1>
            </div>
            <div class="col-md-2">
                <a class="add-new" href="add-user.php">add user</a>
            </div>
            <?php
            $limit   = 5;
            $page    = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
            $page = max(1, $page);
            $offset  = ($page - 1) * $limit;

            $user_id = $_SESSION["user_id"];
            $users_sql = "SELECT * FROM user ORDER BY user_id DESC LIMIT {$offset}, {$limit};";
            ($users_result = mysqli_query($conn, $users_sql)) or die("Query failed");

            ?>
            <div class="col-md-12">
                <?php if (mysqli_num_rows($users_result) > 0) { ?>
                    <table class="content-table">
                        <thead>
                            <th>S.No.</th>
                            <th>Full Name</th>
                            <th>User Name</th>
                            <th>Phone Number</th>
                            <th>Role</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </thead>
                        <tbody>
                            <?php while ($user = mysqli_fetch_assoc($users_result)) { ?>
                                <tr>
                                    <td class='id'><?php echo $user["user_id"]; ?></td>
                                    <td><?php echo $user["first_name"] . " " . $user["last_name"]; ?></td>
                                    <td><?php echo $user["username"]; ?></td>
                                    <td><?php echo $user["phone_number"]; ?></td>
                                    <td><?php echo ($user["role"] == 1) ? "Admin" : "Normal"; ?></td>
                                    <td class='edit'>
                                        <a href='update-user.php?id=<?php echo $user["user_id"]; ?>'>
                                            <i class='fa fa-edit'></i>
                                        </a>
                                    </td>
                                    <td class='delete'>
                                        <a href='delete-user.php?id=<?php echo $user["user_id"]; ?>'>
                                            <i class='fa fa-trash-o'></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php }

                // Pagination
                $user_count_sql = "SELECT COUNT(*) as total FROM user";
                ($user_count_result = mysqli_query($conn, $user_count_sql)) or die("Query Failed");

                $user_count_row = mysqli_fetch_assoc($user_count_result);
                if ((int)$user_count_row["total"] > 0) {
                    $total_records = (int)$user_count_row["total"];
                    $total_pages   = ceil($total_records / $limit);

                    echo "<ul class='pagination admin-pagination'>";

                    if ($page > 1) {
                        echo '<li><a href="users.php?page=' . ($page - 1) . '">Prev</a></li>';
                    }

                    $current_group = ceil($page / 3);
                    $start = ($current_group - 1) * 3 + 1;
                    $end   = min($start + 2, $total_pages);

                    for ($i = $start; $i <= $end; $i++) {
                        $active = ($i == $page) ? "active" : "";
                        echo '<li class="' . $active . '"><a href="users.php?page=' . $i . '">' . $i . "</a></li>";
                    }

                    if ($page < $total_pages) {
                        echo '<li><a href="users.php?page=' . ($page + 1) . '">Next</a></li>';
                    }

                    echo "</ul>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>