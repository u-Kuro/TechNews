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
$author=mysqli_real_escape_string($conn,$_POST['post_author']);
$description=mysqli_real_escape_string($conn,$_POST['postdesc']);
$content=mysqli_real_escape_string($conn,$_POST['postcontent']);
$category=mysqli_real_escape_string($conn,$_POST['category']);
$old_category=mysqli_real_escape_string($conn,$_POST['old_category']);
$date=$_POST['datetime'];
$post_id=mysqli_real_escape_string($conn,$_POST['post_id']);
$imageUrl=mysqli_real_escape_string($conn,$_POST['imageUrl']);
$postUrl=mysqli_real_escape_string($conn,$_POST['postUrl']);
$sql="UPDATE post SET title='{$title}',author='{$author}',description='{$description}',category={$category},post_url='{$postUrl}',post_img ='{$imageUrl}', post_date=STR_TO_DATE('{$date}', '%Y-%m-%dT%H:%i'), content='{$content}' WHERE post_id = {$post_id};";
// echo $sql; //testing

//1 will be minus in old category and 1 will be added in new category
if($old_category != $category){
  $sql.="UPDATE category SET post=post-1 WHERE category_id={$old_category};";
  $sql.="UPDATE category SET post=post+1 WHERE category_id={$category};";
}

$result=mysqli_multi_query($conn,$sql);

if($result){ //if query success
	header("Location: post.php");
}else{
echo "Query Failed";
}
?>
