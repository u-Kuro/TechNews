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

if (isset($_POST["register"])) {
    $validIntFormatNumbersRegx = '/^\+(?:[0-9] ?){6,14}[0-9]$/';

    if (
        empty($_POST["fname"]) ||
        empty($_POST["lname"]) ||
        empty($_POST["username"]) ||
        empty($_POST["password"]) ||
        empty($_POST["phone"])
    ) {
        $error_message = "<div class='alert alert-danger'>All fields are required and must be entered</div>";
    } elseif (!preg_match($validIntFormatNumbersRegx, $_POST["phone"])) {
        $error_message = "<div class='alert alert-danger'>You have entered an invalid phone number (International Format Only)</div>";
    } else {
        $fname       = mysqli_real_escape_string($conn, $_POST["fname"]);
        $lname       = mysqli_real_escape_string($conn, $_POST["lname"]);
        $phoneNumber = mysqli_real_escape_string($conn, $_POST["phone"]);
        $username    = mysqli_real_escape_string($conn, $_POST["username"]);
        $password    = mysqli_real_escape_string($conn, password_hash($_POST["password"], PASSWORD_BCRYPT));

        $sql    = "SELECT username FROM user WHERE username = '{$username}'";
        ($result = mysqli_query($conn, $sql)) or die("Query Failed");

        if (mysqli_num_rows($result) > 0) {
            $error_message = "<p style='color:red;text-align:center;margin:10px 0;'>UserName Already Exists</p>";
        } else {
            $sql1 = "INSERT INTO user (first_name, last_name, username, password, phone_number, role)
                     VALUES ('{$fname}', '{$lname}', '{$username}', '{$password}', '{$phoneNumber}', 0)";

            if (mysqli_query($conn, $sql1)) {
                header("Location: login.php?username=$username");
                exit();
            }
        }
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css" />
    <link rel="stylesheet" href="./css/font-awesome.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="shortcut icon" href="images/icon.png" type="image/x-icon">
</head>

<body>
    <div id="wrapper-admin" class="body-content">
        <div class="container">
            <div class="row">
                <div class="main-container-reg">
                    <div class="logo-container">
                        <img class="logo" src="images/tech-news-withoutbg.png">
                    </div>
                    <h3 class="heading">Sign-Up</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label class="label-un">First Name</label>
                            <input type="text" name="fname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="label-un">Last Name</label>
                            <input type="text" name="lname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="label-un">Phone Number (International Format)</label>
                            <input type="tel" name="phone" pattern="^\+(?:[0-9] ?){6,14}[0-9]$" class="form-control" placeholder="+123456789012" required>
                        </div>
                        <div class="form-group">
                            <label class="label-un">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="label-un">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-footer">
                            <input type="submit" name="register" class="btn btn-primary" value="Register" />
                            <p class="label-un">Already Have an Account? <a href="login.php">Login Here.</a></p>
                        </div>
                    </form>
                    <?php echo $error_message; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>