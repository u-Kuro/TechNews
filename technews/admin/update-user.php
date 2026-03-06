<?php
session_status() === PHP_SESSION_ACTIVE || session_start();
include "../config.php";

if (!isset($_SESSION["username"])) {
    header("Location: ../login.php");
    exit();
} elseif ($_SESSION["user_role"] == 0) {
    header("Location: ../user/home.php");
    exit();
}

include "header.php";
?>
<div id="admin-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="admin-heading">Modify User Details</h1>
            </div>
            <div class="col-md-offset-4 col-md-4">
                <?php
                $user_id = $_GET["id"];
                $fetch_user_sql     = "SELECT * FROM user WHERE user_id = {$user_id}";
                ($fetch_user_result  = mysqli_query($conn, $fetch_user_sql)) or die("Query failed");

                if (mysqli_num_rows($fetch_user_result) > 0) {
                    while ($user_row = mysqli_fetch_assoc($fetch_user_result)) {
                ?>
                        <form method="POST">
                            <div class="form-group">
                                <input type="hidden" name="user_id" value="<?php echo $user_row["user_id"]; ?>">
                            </div>
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="fname" class="form-control" value="<?php echo $user_row["first_name"]; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="lname" class="form-control" value="<?php echo $user_row["last_name"]; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Phone Number (International Format)</label>
                                <input type="tel" name="phone" pattern="^\+(?:[0-9] ?){6,14}[0-9]$" class="form-control" placeholder="+123456789012" value="<?php echo $user_row["phone_number"]; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>User Name</label>
                                <input type="text" name="username" class="form-control" value="<?php echo $user_row["username"]; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>User Role</label>
                                <select class="form-control" name="role">
                                    <?php if ($user_row["role"] == 1) {
                                        echo "<option value='0'>normal User</option>
                                              <option value='1' selected>Admin</option>";
                                    } else {
                                        echo "<option value='0' selected>normal User</option>
                                              <option value='1'>Admin</option>";
                                    } ?>
                                </select>
                            </div>
                            <input type="submit" name="submit" class="btn btn-primary" value="Update" required />
                        </form>
                <?php
                    }
                }

                // Update Info
                if (isset($_POST["submit"])) {
                    $validIntFormatNumbersRegx = '/^\+(?:[0-9] ?){6,14}[0-9]$/';

                    if (
                        empty($_POST["fname"]) ||
                        empty($_POST["lname"]) ||
                        empty($_POST["username"]) ||
                        $_POST["role"] === "" ||
                        empty($_POST["phone"])
                    ) {
                        echo "<div class='alert alert-danger'>All fields are required and Entered </div>";
                    } elseif (!preg_match($validIntFormatNumbersRegx, $_POST["phone"])) {
                        echo "<div class='alert alert-danger'>You have entered an invalid phone number (International Format Only) </div>";
                    } else {
                        $user_id     = mysqli_real_escape_string($conn, $_POST["user_id"]);
                        $fname       = mysqli_real_escape_string($conn, $_POST["fname"]);
                        $lname       = mysqli_real_escape_string($conn, $_POST["lname"]);
                        $phoneNumber = mysqli_real_escape_string($conn, $_POST["phone"]);
                        $user        = mysqli_real_escape_string($conn, $_POST["username"]);
                        $role        = mysqli_real_escape_string($conn, $_POST["role"]);

                        $update_user_sql = "UPDATE user
                                SET first_name   = '{$fname}',
                                    last_name    = '{$lname}',
                                    username     = '{$user}',
                                    role         = '{$role}',
                                    phone_number = '{$phoneNumber}'
                                WHERE user_id = {$user_id}";

                        if (mysqli_query($conn, $update_user_sql)) {
                            header("Location: users.php");
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php";
