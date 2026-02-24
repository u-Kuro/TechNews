<?php
session_status() === PHP_SESSION_ACTIVE || session_start();
include "config.php";
if (isset($_SESSION["user_role"])) {
    if ($_SESSION["user_role"] == 1) {
        header("Location: admin/post.php");
    } else {
        header("Location: user/home.php");
    }
    exit();
} else {
    header("Location: login.php");
    exit();
}
