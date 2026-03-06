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
                    $author = isset($_GET["author"]) ? $_GET["author"] : "Author Not Found";
                    ?>
                    <h2 class="page-heading"><?php echo $author; ?></h2>

                    <?php
                    $limit  = 10;
                    $page   = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
                    if ($page < 1) $page = 1;
                    $offset = ($page - 1) * $limit;

                    $author_posts_sql = "SELECT post.post_id, post.title, category.category_name,
                                   post.post_date, post.content, post.post_img,
                                   post.author, post.category
                            FROM post
                            LEFT JOIN category ON post.category = category.category_id
                            WHERE post.author = '{$author}'
                            ORDER BY post_date DESC
                            LIMIT {$offset}, {$limit}";

                    ($author_posts_result = mysqli_query($conn, $author_posts_sql)) or die("Query failed");

                    if (mysqli_num_rows($author_posts_result) > 0) {
                        while ($post = mysqli_fetch_assoc($author_posts_result)) {
                            $imgHTML    = '<img src="../images/default-image.png" alt="blank" loading="lazy"/>';
                            $image_link = $post["post_img"];

                            if (!empty($image_link)) {
                                $headers = @get_headers($image_link);
                                if ($headers && strpos($headers[0], "200")) {
                                    $imgHTML = '<img src="' . $image_link . '" alt="blank" loading="lazy" onerror="this.src=\'../images/default-image.png\'"/>';
                                }
                            }

                            $post_date  = DateTime::createFromFormat("Y-m-d H:i:s", $post["post_date"])->format("M d, Y");
                            $rawContent = isset($post["content"]) ? $post["content"] : '';

                            if (!empty($rawContent)) {
                                $cleanText = strip_tags(preg_replace('/\s*\[\+\d+\s*chars\]/i', '', $rawContent));
                                $maxChars  = 200;
                                $content   = mb_strlen($cleanText) > $maxChars
                                    ? mb_substr($cleanText, 0, $maxChars) . '...'
                                    : $cleanText;
                            } else {
                                $content = "";
                            }
                    ?>
                            <div class="post-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <a class="post-img" href="single.php?id=<?php echo $post["post_id"]; ?>"><?php echo $imgHTML; ?></a>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="inner-content clearfix">
                                            <h3><a href="single.php?id=<?php echo $post["post_id"]; ?>"><?php echo $post["title"]; ?></a></h3>
                                            <div class="post-information">
                                                <span>
                                                    <i class="fa fa-tags" aria-hidden="true"></i>
                                                    <a href='category.php?cid=<?php echo $post["category"]; ?>'><?php echo $post["category_name"]; ?></a>
                                                </span>
                                                <span>
                                                    <i class="fa fa-user" aria-hidden="true"></i>
                                                    <a href='author.php?author=<?php echo $post["author"]; ?>'><?php echo $post["author"]; ?></a>
                                                </span>
                                                <span>
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                    <?php echo $post_date; ?>
                                                </span>
                                            </div>
                                            <p class="description"><?php echo $content; ?></p>
                                            <a class='read-more pull-right' href="single.php?id=<?php echo $post["post_id"]; ?>">Read More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<h2> No Posts Record Found</h2>";
                    }
                    ?>

                    <?php
                    // Pagination
                    $author_post_count_sql = "SELECT COUNT(*) as total FROM post WHERE author = '" . mysqli_real_escape_string($conn, $author) . "'";
                    $author_post_count_result = mysqli_query($conn, $author_post_count_sql);
                    $author_post_count_row = mysqli_fetch_assoc($author_post_count_result);
                    $total_records = (int)$author_post_count_row["total"];
                    if ($total_records > 0) {
                        $total_pages   = ceil($total_records / $limit);

                        echo "<ul class='pagination admin-pagination'>";

                        if ($page > 1) {
                            echo '<li><a href="author.php?author=' . $author . '&page=' . ($page - 1) . '">Prev</a></li>';
                        }

                        $current_group = ceil($page / 3);
                        $start = ($current_group - 1) * 3 + 1;
                        $end   = min($start + 2, $total_pages);

                        for ($i = $start; $i <= $end; $i++) {
                            $active = ($i == $page) ? "active" : "";
                            echo '<li class="' . $active . '"><a href="author.php?author=' . $author . '&page=' . $i . '">' . $i . "</a></li>";
                        }

                        if ($page < $total_pages) {
                            echo '<li><a href="author.php?author=' . $author . '&page=' . ($page + 1) . '">Next</a></li>';
                        }

                        echo "</ul>";
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