<?php
session_status() === PHP_SESSION_ACTIVE || session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../login.php");
    exit();
} elseif ($_SESSION["user_role"] == 1) {
    header("Location: ../admin/post.php");
    exit();
}

// Dynamic page title
$pagename = basename($_SERVER["PHP_SELF"]);

switch ($pagename) {
    case "single.php":
        if (isset($_GET["id"])) {
            $sql        = "SELECT * FROM post WHERE post_id = {$_GET["id"]}";
            ($result     = mysqli_query($conn, $sql)) or die("Query Failed: single");
            $row        = mysqli_fetch_assoc($result);
            $page_title = $row["title"] . " News";
        } else {
            $page_title = "No Post Found";
        }
        break;

    case "category.php":
        if (isset($_GET["cid"])) {
            $sql        = "SELECT * FROM category WHERE category_id = {$_GET["cid"]}";
            ($result     = mysqli_query($conn, $sql)) or die("Query Failed");
            $row        = mysqli_fetch_assoc($result);
            $page_title = $row["category_name"] . " News";
        } else {
            $page_title = "No Post Found";
        }
        break;

    case "author.php":
        $page_title = isset($_GET["author"]) ? "News by " . $_GET["author"] : "No Post Found";
        break;

    case "search.php":
        $page_title = isset($_GET["search"]) ? $_GET["search"] : "No Search Result Found";
        break;

    default:
        $page_title = "Technology News";
        break;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/font-awesome.css">
    <link rel="stylesheet" href="../css/style2.css">
    <link rel="shortcut icon" href="../images/icon.png" type="image/x-icon">
</head>

<body>
    <div id="header-admin">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <a href="home.php"><img class="logo" src="../images/tech-news-withoutbg.png"></a>
                </div>
                <div class="col-md-offset-9 col-md-1">
                    <button onclick="location.href='../logout.php'" class="admin-logout">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <div id="menu-bar">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $cat_id = isset($_GET["cid"]) ? $_GET["cid"] : null;

                    // Show only categories that have at least one post
                    $sql2    = "SELECT * FROM category WHERE post > 0";
                    ($result2 = mysqli_query($conn, $sql2)) or die("Query failed: Category");

                    if (mysqli_num_rows($result2) > 0) { ?>
                        <ul class='menu'>
                            <li><a href='home.php'>HOME</a></li>
                            <?php while ($row2 = mysqli_fetch_assoc($result2)) {
                                $active = ($cat_id && $row2["category_id"] == $cat_id) ? "active" : "";
                                echo "<li><a class='{$active}' href='category.php?cid={$row2["category_id"]}'>{$row2["category_name"]}</a></li>";
                            } ?>
                        </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>