<?php
session_start();
include "../config.php";
include "header.php";
if(!isset($_SESSION["username"])){
  header("Location: ../login.php");
} else if($_SESSION["user_role"]==0) {
  header("Location: ../user/home.php");
}
$post_id=$_GET['id'];
$cat_id=$_GET['catid']; //There will be 1 minus in the category from which the post has been deleted. 
$sql1="SELECT * FROM post WHERE post_id={$post_id};";
$result=mysqli_query($conn,$sql1) or die("Query Failed :select");
$row=mysqli_fetch_assoc($result);
unlink("upload/".$row['post_img']);     //use delete file from folder


$sql="DELETE FROM post WHERE post_id={$post_id};";
$sql.="UPDATE category SET post=post-1 WHERE category_id={$cat_id}";

if(mysqli_multi_query($conn,$sql)){
	header("Location: post.php");
}else{
	echo "Cannot Delete";
}

?>