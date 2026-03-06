<?php
session_status() === PHP_SESSION_ACTIVE || session_start();
include "config.php";

if (isset($_SESSION["user_role"])) {
    if ($_SESSION["user_role"] == 1) {
        header("Location: admin/post.php");
    } else {
        header("Location: user/home.php");
    }
    exit();
}

$error_message = "";

if (isset($_POST["login"])) {
    if (empty($_POST["username"]) || empty($_POST["password"])) {
        $error_message = "<div class='alert alert-danger'>All fields are required and must be entered</div>";
    } else {
        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $password = $_POST["password"];

        $sql    = "SELECT user_id, username, role, password FROM user WHERE username = '{$username}'";
        ($result = mysqli_query($conn, $sql)) or die("Query Failed");

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $row["password"])) {
                    $_SESSION["user_id"]   = $row["user_id"];
                    $_SESSION["username"]  = $row["username"];
                    $_SESSION["user_role"] = $row["role"];

                    if ($row["role"] == 1) {
                        header("Location: admin/post.php");
                    } else {
                        header("Location: user/home.php");
                    }
                    exit();
                }
            }
        }

        $error_message = "<div class='alert alert-danger'>Username and Password are incorrect</div>";
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css" />
    <link rel="stylesheet" href="./css/font-awesome.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="shortcut icon" href="images/icon.png" type="image/x-icon">
</head>

<body>
    <div id="wrapper-admin2" class="body-content">
        <div class="container">
            <div class="row">
                <div class="main-container">
                    <div class="logo-container">
                        <img class="logo" src="images/tech-news-withoutbg.png">
                    </div>
                    <h3 class="heading">Login</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label class="label-un">Username</label>
                            <?php
                            $username = "";
                            if (isset($_GET["username"]) && !empty($_GET["username"])) {
                                $username = $_GET["username"];
                            }
                            ?>
                            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="label-un">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-footer">
                            <input type="submit" name="login" class="btn btn-primary" value="Login" />
                            <p class="label-un">Don't have an account? <a href="register.php">Register here.</a></p>
                        </div>
                    </form>
                    <?php echo $error_message; ?>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>