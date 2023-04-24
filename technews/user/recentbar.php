<!-- recent posts box -->
<div class="recent-post-container">
    <h4>Recent Posts</h4>
    <?php
    $limit=3;

    $sql="SELECT post.post_id,post.title,category.category_name,post.post_date,post.description,post.post_img,post.author,post.category
    FROM post
    LEFT JOIN category ON post.category=category.category_id ORDER BY post_id DESC LIMIT {$limit}";   //view latest post information

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
    <div class="recent-post">
        <a class="post-img" href="single.php?id=<?php echo $row['post_id'];?>">
            <?php echo $imgHTML;?>
        </a>
        <div class="post-content">
            <h5><a href="single.php?id=<?php echo $row['post_id'];?>"><?php echo $row["title"];?></a></h5>
            <span>
                <i class="fa fa-tags" aria-hidden="true"></i>
                <a href='category.php?cid=<?php echo $row["category"];?>'> <?php echo $row["category_name"];?></a>
            </span>
            <span>
                <i class="fa fa-calendar" aria-hidden="true"></i>
            <?php echo $post_date;?>
            </span>
            <a class="read-more" href="single.php?id=<?php echo $row['post_id'];?>">read more</a>
        </div>
    </div>
<?php }
}
?>
</div>
<!-- /recent posts box -->