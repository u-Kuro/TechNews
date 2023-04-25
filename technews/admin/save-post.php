<?php
session_start();
include "../config.php";
include "header.php";
if(!isset($_SESSION["username"])){
  header("Location: ../login.php");
} else if($_SESSION["user_role"]==0) {
  header("Location: ../user/home.php");
}

$title=mysqli_real_escape_string($conn,$_POST['post_title']);
$author=mysqli_real_escape_string($conn,$_POST['author']);
$description=mysqli_real_escape_string($conn,$_POST['postdesc']);
$content=mysqli_real_escape_string($conn,$_POST['postcontent']);
$categoryid=mysqli_real_escape_string($conn,$_POST['categoryid']);
$date=$_POST['datetime'];
$imageUrl=mysqli_real_escape_string($conn,$_POST['imageUrl']);
$postUrl=mysqli_real_escape_string($conn,$_POST['postUrl']);

$sql= "INSERT INTO post (title,author,description,category,post_url,post_img,post_date,content)
VALUES('{$title}','{$author}','{$description}','{$categoryid}','{$postUrl}','{$imageUrl}',STR_TO_DATE('{$date}', '%Y-%m-%dT%H:%i'),content='{$content}');";

//category counting will continue in the category value table when the category of which the post will be inserted by default was 0 posts.
$sql.="UPDATE category SET post=post+1 WHERE category_id= {$categoryid}";

 if(mysqli_multi_query($conn,$sql)){
  header("Location: post.php");
 }else{
  echo "<div class='alert alert-danger'>Query Failed</div>";
}

?>
