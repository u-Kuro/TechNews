<?php 
session_start();
include "../config.php";
include "header.php";
if(!isset($_SESSION["username"])){
  header("Location: ../login.php");
} else if($_SESSION["user_role"]==0) {
  header("Location: ../user/home.php");
}
?>
  <div id="admin-content">
      <div class="container">
         <div class="row">
             <div class="col-md-12">
                 <h1 class="admin-heading">Add New Post</h1>
             </div>
              <div class="col-md-offset-3 col-md-6">
                  <!-- Form -->
                  <form  action="save-post.php" method="POST" enctype="multipart/form-data">
                      <div class="form-group">
                          <label for="post_title">Title</label>
                          <input type="text" name="post_title" class="form-control" autocomplete="off" required>
                      </div>
                      <div class="form-group">
                          <label for="author">Author</label>
                          <input type="text" name="author" class="form-control" autocomplete="off">
                      </div>
                      <div class="form-group">
                          <label for="exampleInputPassword1"> Description</label>
                          <textarea name="postdesc" class="form-control" rows="5"  required></textarea>
                      </div>
                      <div class="form-group">
                          <label for="exampleInputDate">Date</label>
                          <input type="datetime-local" name="datetime"  class="form-control" id="exampleInputDate" required>
                      </div>
                      <div class="form-group">
                          <label for="exampleInputPassword1">Category</label>
                          <select name="categoryid" class="form-control" required>
                            <option disabled selected value=""> Select Category</option>
                            <?php
                                $sql="SELECT * FROM category";
                                $result=mysqli_query($conn,$sql) or die("Query Failed");
                                if(mysqli_num_rows($result)> 0){
                                    while($row=mysqli_fetch_assoc($result)){
                                        echo "<option value='{$row['category_id']}' required>{$row['category_name']}</option>";
                                    }
                                }
                            ?>
                        </select>
                      </div>
                      <div class="form-group">
                          <label for="imageUrl">Image Url</label>
                          <input type="text" name="imageUrl" class="form-control" autocomplete="off">
                      </div>
                      <div class="form-group">
                          <label for="postUrl">Post Url</label>
                          <input type="text" name="postUrl"  class="form-control">
                      </div>
                      <div class="form-group">
                          <label for="postcontent"> Content</label>
                          <textarea name="postcontent" class="form-control" required rows="5"></textarea>
                      </div>
                      <input type="submit" name="submit" class="btn btn-primary" value="Save" required />
                  </form>
                  <!--/Form -->
              </div>
          </div>
      </div>
  </div>
<?php include "../footer.php"; ?>
