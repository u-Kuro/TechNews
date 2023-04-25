<?php 
session_start();
include "../config.php";
include "header.php";
if(!isset($_SESSION["username"])){
  header("Location: ../login.php");
} else if($_SESSION["user_role"]==0) {
  header("Location: ../user/home.php");
}
//Save User information Into Database
if(isset($_POST["save"])){
    $validIntFormatNumbersRegx = '/^\+(?:[0-9] ?){6,14}[0-9]$/';
    if (empty($_POST["fname"]) || empty($_POST["lname"]) || empty($_POST["user"]) || empty($_POST["password"]) || empty($_POST["phone"]) || $_POST["role"] === "") {
        echo "<div class='alert alert-danger'>All fields are required and Entered </div>";
    } else if(!preg_match($validIntFormatNumbersRegx, $_POST["phone"])){
        echo "<div class='alert alert-danger'>You have entered an invalid phone number (International Format Only) </div>";
    } else {
        $fname=mysqli_real_escape_string($conn,$_POST["fname"]);  //for hacking protection
        $lname=mysqli_real_escape_string($conn,$_POST["lname"]);
        $phoneNumber=mysqli_real_escape_string($conn,$_POST["phone"]);
        $user=mysqli_real_escape_string($conn,$_POST["user"]);
        $password=mysqli_real_escape_string($conn,md5($_POST["password"]));
        $role=mysqli_real_escape_string($conn,$_POST["role"]);
        // Check if username already exists
        // Check query use echo $sql; and after that use die (testing purposes)
        $sql="SELECT username from user WHERE username='{$user}'";
        $result=mysqli_query($conn,$sql) or die("Query failed");
        if(mysqli_num_rows($result) > 0){
            echo "<p style='color:red;text-align:center;margin:10px 0;'>UserName Already Exists</p>";
        } else {
            $sql1="INSERT INTO user (first_name,last_name,username,password,phone_number,role)
                    VALUES ('{$fname}','{$lname}','{$user}','{$password}','{$phoneNumber}','{$role}')";
            if(mysqli_query($conn,$sql1)){
                echo "Successfully Added.";
                header("Location: users.php");
            }
        }
    }
}
?>
  <div id="admin-content">
      <div class="container">
          <div class="row">
              <div class="col-md-12">
                  <h1 class="admin-heading">Add User</h1>
              </div>
              <div class="col-md-offset-3 col-md-6">
                  <!-- Form Start -->
                  <form  action="<?php $_SERVER["PHP_SELF"]; ?>" method ="POST" autocomplete="off">
                      <div class="form-group">
                          <label>First Name</label>
                          <input type="text" name="fname" class="form-control" placeholder="First Name" required>
                      </div>
                          <div class="form-group">
                          <label>Last Name</label>
                          <input type="text" name="lname" class="form-control" placeholder="Last Name" required>
                      </div>
                      <div class="form-group">
                          <label>Phone Number (International Format)</label>
                          <input type="tel" name="phone" pattern="^\+(?:[0-9] ?){6,14}[0-9]$" class="form-control" placeholder="+123456789012" required>
                      </div>
                      <div class="form-group">
                          <label>User Name</label>
                          <input type="text" name="user" class="form-control" placeholder="Username" required>
                      </div>
                      <div class="form-group">
                          <label>Password</label>
                          <input type="password" name="password" class="form-control" placeholder="Password" required>
                      </div>
                      <div class="form-group">
                          <label>User Role</label>
                          <select class="form-control" name="role" >
                              <option value="0">Normal User</option>
                              <option value="1">Admin</option>
                          </select>
                      </div>
                      <input type="submit"  name="save" class="btn btn-primary" value="Save" required />
                  </form>
                   <!-- Form End-->
               </div>
           </div>
       </div>
   </div>
<?php include "../footer.php"; ?>
