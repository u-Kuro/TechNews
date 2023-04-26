<?php
session_start();
include "../config.php";
include "header.php";
if(!isset($_SESSION["username"])){
  header("Location: ../login.php");
} else if($_SESSION["user_role"]==0) {
  header("Location: ../user/home.php");
}
$category_id=$_GET["id"];

$sql="DELETE FROM post WHERE category = {$category_id}";
if(mysqli_query($conn,$sql)){
  $sql1="DELETE FROM category WHERE category_id = {$category_id}";
  if(mysqli_query($conn,$sql1)){
    header("Location:category.php");
  } else {
    echo "<p style='color:red;text-align:center;margin:10px 0;'>Can't be Deleted</p>";
  }
} else {
  echo "<p style='color:red;text-align:center;margin:10px 0;'>Can't be Deleted</p>";
}
mysqli_close($conn);


?>
