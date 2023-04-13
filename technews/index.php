<?php
    session_start();
    include "config.php";
    if(isset($_SESSION["user_role"])){
        $_SESSION["user_role"]==1 ? header("Location: admin/post.php") : header("Location: user/home.php");
    } else {
        header("Location: login.php");
    }
?>
