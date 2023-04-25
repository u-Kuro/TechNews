<?php 
session_start();
if(!isset($_SESSION["username"])){
    header("Location: ../login.php");
} else if($_SESSION["user_role"]==1) {
    header("Location: ../admin/post.php");
}
include "../config.php";
include 'header.php';
?>
<div id="main-content">
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <!-- post-container -->
            <div class="post-container">
            <?php
                $limit=3;
                if(isset($_GET["page"])){
                $page=$_GET["page"];
                }else{
                    $page=1;
                }
                $offset=($page-1) * $limit;

          $sql="SELECT post.post_id,post.title,category.category_name,post.post_date,post.description,post.post_img,post.author,post.author,post.category FROM post
          LEFT JOIN category ON post.category=category.category_id
          ORDER BY post_date DESC LIMIT {$offset}, {$limit}";   //view latest post information

          $result=mysqli_query($conn,$sql) or die("Query failed :fetch posts");
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
  <a href='category.php?cid=<?php echo $row["category"];?>'> <?php echo $row["category_name"];?></a>
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
                echo "<h2> No Record Found</h2>";
            }?>

<?php
// Show pagination codes
$sql1 = "SELECT * FROM post"; // Fetch posts from database
$result1 = mysqli_query($conn, $sql1) or die("Query Failed");
if (mysqli_num_rows($result1) > 0) {
    $total_records = mysqli_num_rows($result1);
    $limit = 3; // Number of records to display per page
    $total_pages = ceil($total_records / $limit); // Calculate total number of pages
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Get current page from URL parameter
    $start = ($current_page - 1) * $limit; // Calculate starting record for current page

    echo "<ul class='pagination admin-pagination'>";

    if ($current_page > 1) { // If not on the first page, display "Prev" button
        echo '<li><a href="home.php?page=' . ($current_page - 1) . '">Prev</a></li>';
    }

    // Display up to 3 pages at a time
    $end = min($current_page + 2, $total_pages); // Calculate ending page
    $start = max($end - 2, 1); // Calculate starting page
    for ($i = $start; $i <= $end; $i++) {
        $active = ($i == $current_page) ? "active" : ""; // Add "active" class to current page
        echo '<li class="' . $active . '"><a href="home.php?page=' . $i . '">' . $i . '</a></li>';
    }

    if ($current_page + 2 < $total_pages) { // If not on the last set of 3 pages, display "Next" button
        echo '<li><a href="home.php?page=' . ($current_page + 3) . '">Next</a></li>';
    }

    echo "</ul>";
}
?>






            </div><!-- /post-container -->
        </div>
        <div id="sidebar" class="col-md-4">
          <?php 
              include 'searchbar.php';
          ?>
        </div>
    </div>
  </div>
</div>
<?php include '../footer.php'; ?>
