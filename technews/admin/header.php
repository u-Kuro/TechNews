<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ADMIN Panel</title>
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
                    <a href="post.php"><img class="logo" src="../images/tech-news-withoutbg.png"></a>
                </div>
                <div class="col-md-offset-9 col-md-1">
                    <a href="../logout.php" class="admin-logout">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div id="admin-menubar">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="admin-menu">
                        <li><a href="post.php">Post</a></li>
                        <?php if ($_SESSION["user_role"] == 1) { ?>
                            <li><a href="category.php">Category</a></li>
                            <li><a href="users.php">Users</a></li>
                            <li><a href="settings.php">Settings</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>