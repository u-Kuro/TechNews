<?php 
include "../config.php";
session_start();
include 'header.php';  
?>
    <div id="main-content">
      <div class="container">
        <div class="row">
            <div class="col-md-8">
                <!-- post-container -->
                <div class="post-container">
                  <?php
                  if(isset($_GET['cid'])){ //get category_id from url bar
                  $cat_id=$_GET['cid'];
                  }

                  $sql2="SELECT * FROM category WHERE category_id={$cat_id}";
                  $result2=mysqli_query($conn,$sql2) or die("Query Failed");
                  $row2=mysqli_fetch_assoc($result2);
                   ?>

                  <h2 class="page-heading"><?php echo $row2['category_name']; ?></h2>
                  <?php
                    if(isset($_GET['cid'])){ 
                    $cat_id=$_GET['cid'];
                    }
                      $limit=3;
                      if(isset($_GET["page"])){
                      $page=$_GET["page"];
                      }else{
                          $page=1;
                      }
                      $offset=($page-1) * $limit;

                //Aggregate SQL command will show related posts from the same category whose operation is coming
                $sql="SELECT post.post_id,post.title,category.category_name,post.post_date,post.description,post.author,post.post_img,post.author,post.category FROM post
                LEFT JOIN category ON post.category=category.category_id
                WHERE post.category={$cat_id}
                ORDER BY post_date DESC LIMIT {$offset}, {$limit}";

                $result=mysqli_query($conn,$sql) or die("Query failed ");
                if(mysqli_num_rows($result) > 0 ){
                  while($row = mysqli_fetch_assoc($result)) {
                        $imgHTML = '<img src="../images/default-image.png" alt="blank" loading="lazy"/>';
                        $image_link = $row['post_img'];
                        if (!empty($image_link)) {
                            $headers = @get_headers($image_link);
                            if($headers && strpos($headers[0], '200')) {
                                $imgHTML = '<img src="'.$image_link.'" alt="blank" loading="lazy" onerror="this.src=\'../images/default-image.png\'"/>';
                            }
                        }
                        $post_date = DateTime::createFromFormat('Y-m-d H:i:s', $row['post_date'])->format('M d, Y');
                  ?>
                      <div class="post-content">
                          <div class="row">
                              <div class="col-md-4">
                                  <a class="post-img" href="single.php?id=<?php echo $row['post_id'];?>"><?php echo $imgHTML;?></a>
                              </div>
                              <div class="col-md-8">
                                  <div class="inner-content clearfix">
                                      <h3><a href="single.php?id=<?php echo $row['post_id'];?>"><?php echo $row["title"];?></a></h3>
                                      <div class="post-information">
                                          <span>
                                              <i class="fa fa-tags" aria-hidden="true"></i>
    <a href='category.php?cid=<?php echo $row["category"]; ?>'><?php echo $row["category_name"];?></a>
                                          </span>
                                          <span>
                                              <i class="fa fa-user" aria-hidden="true"></i>
  <a href='author.php?author=<?php echo $row['author']; ?>'><?php echo $row["author"];?></a>
                                          </span>
                                          <span>
                                              <i class="fa fa-calendar" aria-hidden="true"></i>
                                              <?php echo $post_date;?>
                                          </span>
                                      </div>
                                      <p class="description">
                                      <?php echo substr($row["description"],0,140)."....";?>
                                      </p>
                                      <a class='read-more pull-right' href="single.php?id=<?php echo $row['post_id'];?>">Read More</a>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <?php
                      }
                  }else{
                      echo "<h2> No Posts Record Found</h2>";
                  }?>

<?php
// Show pagination codes
$sql1 = "SELECT * FROM category WHERE category_id={$cat_id}";
$result1 = mysqli_query($conn, $sql1) or die("Query Failed");
$row = mysqli_fetch_assoc($result1);

if (mysqli_num_rows($result1) > 0) {
  $total_records = $row['post'];
  $limit = 3; // Set the limit of records to display per page
  $total_pages = ceil($total_records / $limit); // Calculate the total number of pages

  // Calculate the range of pages to display
  $current_group = ceil($page / 3); // Calculate the current page group
  $start = ($current_group - 1) * 3 + 1; // Calculate the start page of the current group
  $end = min($start + 2, $total_pages); // Calculate the end page of the current group

  echo "<ul class='pagination admin-pagination'>";
  if ($page > 1) {
    echo '<li><a href="category.php?cid=' . $cat_id . '&page=' . ($page - 1) . '">Prev</a></li>';
  }

  for ($i = $start; $i <= $end; $i++) {
    // Active class code
    if ($i == $page) {
      $active = "active";
    } else {
      $active = "";
    }
    echo '<li class="' . $active . '"><a href="category.php?cid=' . $cat_id . '&page=' . $i . '">' . $i . '</a></li>';
  }

  if ($current_group * 3 < $total_pages) {
    echo '<li><a href="category.php?cid=' . $cat_id . '&page=' . ($end + 1) . '">Next</a></li>';
  }
  echo "</ul>";
}
?>


                </div><!-- /post-container -->
            </div>
            <div id="sidebar" class="col-md-4">
            <?php 
                include 'searchbar.php'; 
                include 'recentbar.php';
            ?>
            </div>
        </div>
      </div>
    </div>
<?php include '../footer.php'; ?>
