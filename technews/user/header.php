<?php
// echo "<pre>";
// print_r($_SERVER);
// echo "</pre>";
// echo "$_SERVER[PHP_SELF]";
//Dynamic Website Title coding Start
$pagename=basename($_SERVER['PHP_SELF']);
//echo $pagename;
switch ($pagename) {
    case 'single.php':
        if(isset($_GET['id'])){
            $sql="SELECT * FROM post WHERE post_id={$_GET['id']}";
            $result=mysqli_query($conn,$sql) or die("Query Failed : single");
            $row=mysqli_fetch_assoc($result);
            $page_title=$row['title']. " News";
        } else {
            $page_title="No Post Found";
        }
        break;

    case 'category.php':
        if(isset($_GET['cid'])){
            $sql="SELECT * FROM category WHERE category_id ={$_GET['cid']}";
            $result=mysqli_query($conn,$sql) or die("Query Failed :");
            $row=mysqli_fetch_assoc($result);
            $page_title=$row['category_name']. " News";
        } else {
            $page_title="No Post Found";
        }
        break;

    case 'author.php':
        if(isset($_GET['author'])){
            $page_title="News by ".$_GET['author'];
        } else {
            $page_title="No Post Found";
        }
        break;

    case 'search.php':
        if(isset($_GET['search'])){
            $page_title=$_GET['search'];
        } else {
            $page_title="No Search Result Found";
        }
        break;

    default:
        $page_title="Technology News";
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $page_title; ?></title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="../css/font-awesome.css">
    <!-- Custom stlylesheet -->
    <link rel="stylesheet" href="../css/style2.css">
    <link rel="shortcut icon" href="../images/icon.png" type="image/x-icon">
</head>
<body>
<!-- HEADER -->
<div id="header-admin">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- LOGO -->
            <div class="col-md-2">
                <a href="home.php"><img class="logo" src="../images/tech-news-withoutbg.png"></a>
            </div>
            <!-- /LOGO -->
              <!-- LOGO-Out -->
            <div class="col-md-offset-7 col-md-3">
                <button onclick="location.href='../logout.php'" class="admin-logout">Hello <?php echo $_SESSION["username"] ?>, logout</button>
            </div>
            <!-- /LOGO-Out -->
        </div>
    </div>
</div>
<!-- /HEADER -->
<!-- Menu Bar -->
<div id="menu-bar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
              <?php
              if(isset($_GET['cid'])){
                $cat_id=$_GET['cid'];
             }

             //print Dynamic menus catagory pages
              //show the category that has a post inside it
              $sql2="SELECT * FROM category WHERE post > 0";
              $result2=mysqli_query($conn,$sql2) or die("Query failed :Category");
              if(mysqli_num_rows($result2) > 0 ){
                 $active="";
               ?>
                <ul class='menu'>
                  <li><a class='' href='<?php echo 'home.php'; ?>'>HOME</a></li>
                    <?php
                    while($row2 = mysqli_fetch_assoc($result2)) {
                    if(isset($_GET['cid'])){
                      if($row2['category_id']==$cat_id){
                        $active="active";
                      }else{
                       $active="";
                      }
                   }
  echo "<li><a class='{$active}' href='category.php?cid={$row2['category_id']}'>{$row2['category_name']}</a></li>";
              } ?>
                </ul>
              <?php } ?>
            </div>
        </div>
    </div>
</div>
<!-- /Menu Bar -->
