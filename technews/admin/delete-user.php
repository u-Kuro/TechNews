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

$user_id = $_GET["id"];

$sql = "DELETE FROM user WHERE user_id = {$user_id}";

if (mysqli_query($conn, $sql)) {
    header("Location:users.php");
    exit();
} else {
    echo "<p style='color:red;text-align:center;margin:10px 0;'>Can\'t Delete</p>";
}
mysqli_close($conn);
