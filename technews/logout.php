<?php
session_start();
session_unset(); //variables value removed
session_destroy();
include "config.php";
header("Location: login.php"); //go to index.php
?>
