<?php
session_status() === PHP_SESSION_ACTIVE || session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../login.php");
    exit();
} elseif ($_SESSION["user_role"] == 1) {
    header("Location: ../admin/post.php");
    exit();
}
?>
<!-- recent posts box -->
<div class="recent-post-container">
    <h4>Recent Posts</h4>
    <?php
    $limit = 3;
    $recent_posts_sql   = "SELECT post.post_id, post.title, category.category_name,
                     post.post_date, post.post_img, post.author, post.category
              FROM post
              LEFT JOIN category ON post.category = category.category_id
              ORDER BY post_date DESC
              LIMIT {$limit}";

    ($recent_posts_result = mysqli_query($conn, $recent_posts_sql)) or die("Query failed");

    if (mysqli_num_rows($recent_posts_result) > 0) {
        while ($recent_post_row = mysqli_fetch_assoc($recent_posts_result)) {
            $imgHTML    = '<img src="../images/default-image.png" alt="blank" loading="lazy"/>';
            $image_link = $recent_post_row["post_img"];

            if (!empty($image_link)) {
                $headers = @get_headers($image_link);
                if ($headers && strpos($headers[0], "200")) {
                    $imgHTML = '<img src="' . $image_link . '" alt="blank" loading="lazy" onerror="this.src=\'../images/default-image.png\'"/>';
                }
            }

            $post_date = DateTime::createFromFormat("Y-m-d H:i:s", $recent_post_row["post_date"])->format("M d, Y");
    ?>
            <div class="recent-post">
                <a class="post-img" href="single.php?id=<?php echo $recent_post_row["post_id"]; ?>">
                    <?php echo $imgHTML; ?>
                </a>
                <div class="post-content">
                    <h5><a href="single.php?id=<?php echo $recent_post_row["post_id"]; ?>"><?php echo $recent_post_row["title"]; ?></a></h5>
                    <span>
                        <i class="fa fa-tags" aria-hidden="true"></i>
                        <a href='category.php?cid=<?php echo $recent_post_row["category"]; ?>'><?php echo $recent_post_row["category_name"]; ?></a>
                    </span>
                    <span>
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <?php echo $post_date; ?>
                    </span>
                    <a class="read-more" href="single.php?id=<?php echo $recent_post_row["post_id"]; ?>">read more</a>
                </div>
            </div>
    <?php
        }
    }
    ?>
</div>
<!-- /recent posts box -->