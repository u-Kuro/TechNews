<?php
session_status() === PHP_SESSION_ACTIVE || session_start();
session_unset(); //variables value removed
session_destroy();
header("Location: login.php"); //go to index.php
exit();
