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
        <h1 class="admin-heading">Update Post</h1>
    </div>
    <div class="col-md-offset-3 col-md-6">
     <?php
     $post_id=$_GET['id']; //get id which we want to update
     $sql="SELECT post.post_id,post.title,post.author,post.description,category.category_name,post.post_date,post.post_img,post.category,post.content,post.post_url FROM post
     LEFT JOIN category ON post.category=category.category_id
     WHERE post_id={$post_id}";

        $result=mysqli_query($conn,$sql) or die("Query failed ");
        if(mysqli_num_rows($result) > 0 ){
        while($row = mysqli_fetch_assoc($result)) {
            $html_date = date('Y-m-d\TH:i', strtotime($row['post_date']));
     ?>
        <!-- Form for show edit-->
        <form action="save-update-post.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="form-group">
                <input type="hidden" name="post_id"  class="form-control" value="<?php echo $row['post_id']?>" placeholder="">
            </div>
            <div class="form-group">
                <label for="exampleInputTile">Title</label>
                <input type="text" name="post_title"  class="form-control" id="exampleInputTitle" value="<?php echo $row['title']?>">
            </div>
            <div class="form-group">
                <label for="exampleInputAuthor">Author</label>
                <input type="text" name="post_author"  class="form-control" id="exampleInputAuthor" value="<?php echo $row['author']?>">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1"> Description</label>
                <textarea name="postdesc" class="form-control"  required rows="5">
                <?php echo $row['description']?>
                </textarea>
            </div>
            <div class="form-group">
                <label for="exampleInputDate">Date</label>
                <input type="datetime-local" name="datetime"  class="form-control" id="exampleInputDate" value="<?php echo $html_date?>">
            </div>
            <div class="form-group">
                <label for="exampleInputCategory">Category</label>
                <select class="form-control" name="category">
                    <?php
                    $sql1="SELECT * FROM category";
                    $result1=mysqli_query($conn,$sql1) or die("Query Failed");

                   if(mysqli_num_rows($result1)> 0){
                    while($row1=mysqli_fetch_assoc($result1)){
                        if($row["category"]==$row1["category_id"]){ //foriegnkey4== 4primarykey
                            $selected="selected";
                        }else{
                            $selected="";
                        }
                     echo "<option {$selected} value='{$row1['category_id']}'> {$row1['category_name']} </option>";

                     }
                    }
                 ?>
              </select>
              <input type="hidden" name="old_category" value="<?php echo $row['category']; ?>">
            </div>
            <div class="form-group">
                <label for="imageUrl">Image Url</label>
                <input type="text" name="imageUrl"  class="form-control" value="<?php echo htmlspecialchars($row['post_img'])?>">
            </div>
            <div class="form-group">
                <label for="postUrl">Post Url</label>
                <input type="text" name="imageUrl"  class="form-control" value="<?php echo $row['post_url']?>">
            </div>
            <div class="form-group">
                <label for="postcontent"> Content</label>
                <textarea name="postcontent" class="form-control"  required rows="5">
                <?php echo $row['content']?>
                </textarea>
            </div>
            <input type="submit" name="submit" class="btn btn-primary" value="Update" />
        </form>
        <!-- Form End -->
        <?php
        } //while close
     }else{
         echo "Result Not Found";
     }?>
      </div>
    </div>
  </div>
</div>
<?php include "../footer.php"; ?>
