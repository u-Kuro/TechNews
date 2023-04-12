<?php
    session_start();
    include "config.php";
    if(isset($_SESSION["user_role"])){
        $_SESSION["role"]==1 ? header("Location: {$hostname}/admin/post.php") : header("Location: {$hostname}/user/home.php");
    } else {
        header("Location: login.php");
    }
?>
