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

$title        = mysqli_real_escape_string($conn, $_POST["post_title"]);
$author       = mysqli_real_escape_string($conn, $_POST["post_author"]);
$description  = mysqli_real_escape_string($conn, $_POST["postdesc"]);
$content      = mysqli_real_escape_string($conn, $_POST["postcontent"]);
$category     = mysqli_real_escape_string($conn, $_POST["category"]);
$old_category = mysqli_real_escape_string($conn, $_POST["old_category"]);
$date         = $_POST["datetime"];
$post_id      = mysqli_real_escape_string($conn, $_POST["post_id"]);
$imageUrl     = mysqli_real_escape_string($conn, $_POST["imageUrl"]);
$postUrl      = mysqli_real_escape_string($conn, $_POST["postUrl"]);

$update_post_sql = "UPDATE post
        SET title       = '{$title}',
            author      = '{$author}',
            description = '{$description}',
            category    = {$category},
            post_url    = '{$postUrl}',
            post_img    = '{$imageUrl}',
            post_date   = STR_TO_DATE('{$date}', '%Y-%m-%dT%H:%i'),
            content     = '{$content}'
        WHERE post_id = {$post_id};";

// Update category counts if category changed
if ($old_category != $category) {
    $update_post_sql .= "UPDATE category SET post = post - 1 WHERE category_id = {$old_category};";
    $update_post_sql .= "UPDATE category SET post = post + 1 WHERE category_id = {$category};";
}

$update_post_result = mysqli_multi_query($conn, $update_post_sql);

if ($update_post_result) {
    header("Location: post.php");
    exit();
} else {
    echo "Query Failed";
}
