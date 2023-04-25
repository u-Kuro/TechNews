<?php 
include "../config.php";
session_start();
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
              <div class="col-md-10">
                  <h1 class="admin-heading">All Posts</h1>
              </div>
              <div class="col-md-2">
                  <a class="add-new" href="add-post.php">add post</a>
              </div>
              <div class="col-md-12">
              <?php
              $limit=5;
              if(isset($_GET["page"])){  //first time page refresh error solve
                $page=$_GET["page"];
              }else{
                  $page=1;
              }
              $offset=($page-1) * $limit;  //here is offset logic

              //This is secure query for selected coloumns
              if($_SESSION["user_role"]==1){  //means normal user hai toh redirect
                $sql="SELECT post.post_id,post.title,category.category_name,post.post_date,post.author,post.category FROM post
                LEFT JOIN category ON post.category=category.category_id
                ORDER BY post_date DESC LIMIT {$offset}, {$limit}";   //view latest post information
              }
              $result=mysqli_query($conn,$sql) or die("Query failed ");
              if(mysqli_num_rows($result) > 0 ){
               ?>
                  <table class="content-table">
                      <thead>
                          <th>S.No.</th>
                          <th>Title</th>
                          <th>Category</th>
                          <th>Date</th>
                          <th>Author</th>
                          <th>Edit</th>
                          <th>Delete</th>
                      </thead>
                      <tbody>
                      <?php
                      $serial_number=$offset+1;
                      while($row = mysqli_fetch_assoc($result)) {
                          $post_date = DateTime::createFromFormat('Y-m-d H:i:s', $row['post_date'])->format('M d, Y');
                          ?>
                          <tr>
                              <!-- <td class='id'><?php //echo $row["post_id"];?></td> don't show post id instead of this we will use serial number according to offset -->
                              <!-- <td class='id'><?php //echo $offset;?></td> -->
                              <td class='id'><?php echo $serial_number; ?></td>
                              <td><?php echo $row["title"];?></td>
                              <td><?php echo $row["category_name"];?></td>
                              <td><?php echo $post_date;?></td>
                              <td><?php echo $row["author"];?></td>
                              <td class='edit'><a href='update-post.php?id=<?php echo $row["post_id"];?>'><i class='fa fa-edit'></i></a></td>
                              <td class='delete'><a href='delete-post.php?id=<?php echo $row["post_id"];?>&catid=<?php echo $row["category"];?>'><i class='fa fa-trash-o'></i></a></td>
                          </tr>
                          <?php
                          $serial_number++; //for increments
                        } ?>
                      </tbody>
                  </table>

                  <?php
// Show pagination codes
if ($_SESSION["user_role"] == 1) {
  $sql1 = "SELECT * FROM post"; // The user who is there will see the post
}

$result1 = mysqli_query($conn, $sql1) or die("Query Failed");
if (mysqli_num_rows($result1) > 0) {
  $total_records = mysqli_num_rows($result1);
  $limit = 5; // Maximum rows per page
  $total_pages = ceil($total_records / $limit);

  echo "<ul class='pagination admin-pagination'>";
  if ($page > 1) {
    echo '<li><a href="post.php?page=' . ($page - 1) . '">Prev</a></li>';
  }

  $current_group = ceil($page / 3);
  $start = ($current_group - 1) * 3 + 1;
  $end = min($start + 2, $total_pages);

  for ($i = $start; $i <= $end; $i++) {
    // Active class code
    if ($i == $page) {
      $active = "active";
    } else {
      $active = "";
    }
    echo '<li class="' . $active . '"><a href="post.php?page=' . $i . '">' . $i . '</a></li>';
  }

  if ($current_group * 3 < $total_pages) {
    echo '<li><a href="post.php?page=' . ($end + 1) . '">Next</a></li>';
  }
  echo "</ul>";
}
              }
?>


              </div>
          </div>
      </div>
  </div>
<?php include "../footer.php"; ?>
