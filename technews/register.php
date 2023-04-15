<?php
    session_start();
    if(isset($_SESSION["user_role"])){
        $_SESSION["user_role"]==1 ? header("Location: admin/post.php") : header("Location: user/home.php");
    }
    include "config.php";
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
                    <div class="col-md-offset-4 col-md-4">
                        <img class="logo" src="images/tech-news.png">
                        <h3 class="heading">Sign-In</h3>
                        <!-- Form Start -->
                        <form  action="<?php $_SERVER['PHP_SELF']; ?>" method ="POST">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="fname" class="form-control" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="lname" class="form-control" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" placeholder="" required>
                            </div>
                            <div class="form-footer">
                                <input type="submit" name="register" class="btn btn-primary" value="Register" />
                                <p>Already Have an Account? <a href="login.php">Login Here.</a></p>
                            </div>
                        </form>
                        <!-- /Form  End -->
                        <?php
                        if(isset($_POST["register"])){
                            //if field are empty
                            if (empty($_POST["fname"]) || empty($_POST["lname"]) || empty($_POST["username"]) || empty($_POST["password"])) {
                                echo "<div class='alert alert-danger'>All fieds are required and Entered </div>";
                            } else {
                                $fname=mysqli_real_escape_string($conn,$_POST["fname"]);  //for hacking protection
                                $lname=mysqli_real_escape_string($conn,$_POST["lname"]);
                                $username=mysqli_real_escape_string($conn,$_POST["username"]);
                                $password=mysqli_real_escape_string($conn,md5($_POST["password"]));
                                $sql="SELECT username from user WHERE username='{$username}'";
                                $result=mysqli_query($conn,$sql) or die("Query Failed");
                                if(mysqli_num_rows($result) > 0){
                                    echo "<p style='color:red;text-align:center;margin:10px 0;'>UserName Already Exists</p>";
                                } else {
                                    $sql1="INSERT INTO user(first_name,last_name,username,password,role)
                                        VALUES ('{$fname}','{$lname}','{$username}','{$password}','{0}')";
                                    if(mysqli_query($conn,$sql1)){
                                        header("Location: login.php?username=$username");
                                    }
                                }
                            }//sub else close
                        } //root if close
                      ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
