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
                     $post_id= $_GET['id'];
                     $sql="SELECT post.post_id,post.title,category.category_name,post.post_date,post.author,post.description,post.post_img,post.author,post.category,post.content,post.post_url FROM post
                     LEFT JOIN category ON post.category=category.category_id
                     WHERE post_id={$post_id} ";

                     $result=mysqli_query($conn,$sql) or die("Query failed ");
                     if(mysqli_num_rows($result) > 0 ){
                       while($row = mysqli_fetch_assoc($result)) {
                        $imgHTML = '';
                        $image_link = $row['post_img'];
                        if (!empty($image_link)) {
                            $headers = @get_headers($image_link);
                            if($headers && strpos($headers[0], '200')) {
                                $imgHTML = '<img src="'.$image_link.'" alt="blank" loading="lazy" onerror="this.style.display=\'none\';"/>';
                            }
                        }
                        $post_date = DateTime::createFromFormat('Y-m-d H:i:s', $row['post_date'])->format('M d, Y');
                        $content = $row['content'];
                        $contentUrl = $row['post_url'];
                        if(!empty($contentUrl)){
                            $content = preg_replace("/\[\+\d+ chars\]/", "<a href='$contentUrl'>see more</a>", $content);
                        } else {
                            $content = preg_replace("/\[\+\d+ chars\]/", "", $content);
                        }
                    ?>
                        <div class="post-content single-post">
                            <h3><?php echo $row['title'];?></h3>
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
                            <?php echo $imgHTML;?>
                            <p class="description">
                                <?php echo $content;?>
                            </p>
                        </div>
                        <?php
                        }
                    }
                    ?>
                    </div>
                    <!-- /post-container -->
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
