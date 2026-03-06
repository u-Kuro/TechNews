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

$post_id = $_GET["id"];
$cat_id  = $_GET["catid"];

$select_post_sql = "SELECT * FROM post WHERE post_id = {$post_id};";
($post_result    = mysqli_query($conn, $select_post_sql)) or die("Query Failed: select");
$post_row        = mysqli_fetch_assoc($post_result);
unlink("upload/" . $post_row["post_img"]);

$delete_post_sql  = "DELETE FROM post WHERE post_id = {$post_id};";
$delete_post_sql .= "UPDATE category SET post = post - 1 WHERE category_id = {$cat_id}";

if (mysqli_multi_query($conn, $delete_post_sql)) {
    header("Location: post.php");
    exit();
} else {
    echo "Cannot Delete";
}
