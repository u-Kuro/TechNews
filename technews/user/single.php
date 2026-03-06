<?php
session_status() === PHP_SESSION_ACTIVE || session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../login.php");
    exit();
} elseif ($_SESSION["user_role"] == 1) {
    header("Location: ../admin/post.php");
    exit();
}

include "../config.php";
include "header.php";
?>
<div id="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="post-container">
                    <?php
                    $post_id = $_GET["id"];
                    $fetch_post_sql     = "SELECT post.post_id, post.title, category.category_name,
                                       post.post_date, post.author, post.description,
                                       post.post_img, post.category, post.post_url
                                FROM post
                                LEFT JOIN category ON post.category = category.category_id
                                WHERE post_id = {$post_id}";

                    ($fetch_post_result = mysqli_query($conn, $fetch_post_sql)) or die("Query failed");

                    if (mysqli_num_rows($fetch_post_result) > 0) {
                        while ($post_row = mysqli_fetch_assoc($fetch_post_result)) {
                            $imgHTML    = "";
                            $image_link = $post_row["post_img"];

                            if (!empty($image_link)) {
                                $headers = @get_headers($image_link);
                                if ($headers && strpos($headers[0], "200")) {
                                    $imgHTML = '<img src="' . $image_link . '" alt="blank" loading="lazy" onerror="this.style.display=\'none\';"/>';
                                }
                            }

                            $post_date   = DateTime::createFromFormat("Y-m-d H:i:s", $post_row["post_date"])->format("M d, Y");
                            $description = $post_row["description"];
                            $contentUrl  = $post_row["post_url"];

                            if (!empty($contentUrl)) {
                                $description = $description . "<a href='$contentUrl'>see more</a>";
                            }
                    ?>
                            <div class="post-content single-post">
                                <h3><?php echo $post_row["title"]; ?></h3>
                                <div class="post-information">
                                    <span>
                                        <i class="fa fa-tags" aria-hidden="true"></i>
                                        <a href='category.php?cid=<?php echo $post_row["category"]; ?>'><?php echo $post_row["category_name"]; ?></a>
                                    </span>
                                    <span>
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        <a href='author.php?author=<?php echo $post_row["author"]; ?>'><?php echo $post_row["author"]; ?></a>
                                    </span>
                                    <span>
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                        <?php echo $post_date; ?>
                                    </span>
                                </div>
                                <?php echo $imgHTML; ?>
                                <p class="description"><?php echo $description; ?></p>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div><!-- /post-container -->
            </div>
            <div id="sidebar" class="col-md-4">
                <?php
                include "searchbar.php";
                include "recentbar.php";
                ?>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>