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
                    if (isset($_GET["search"])) {
                        $search_term = mysqli_real_escape_string($conn, $_GET["search"]);
                    }
                    ?>
                    <h2 class="page-heading">Search: <?php echo $search_term; ?></h2>

                    <?php
                    if (isset($_GET["search"])) {
                        $search_term = $_GET["search"];
                    }

                    $limit  = 3;
                    $page   = isset($_GET["page"]) ? $_GET["page"] : 1;
                    $offset = ($page - 1) * $limit;

                    $sql = "SELECT post.post_id, post.title, category.category_name,
                                   post.post_date, post.content, post.post_img,
                                   post.author, post.category
                            FROM post
                            LEFT JOIN category ON post.category = category.category_id
                            WHERE post.title       LIKE '%{$search_term}%'
                               OR post.content     LIKE '%{$search_term}%'
                               OR post.description LIKE '%{$search_term}%'
                               OR post.author      LIKE '%{$search_term}%'
                               OR category.category_name LIKE '%{$search_term}%'
                            ORDER BY post_date DESC
                            LIMIT {$offset}, {$limit}";

                    ($result = mysqli_query($conn, $sql)) or die("Query failed: Fetch Search Items");

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $imgHTML    = '<img src="../images/default-image.png" alt="blank" loading="lazy"/>';
                            $image_link = $row["post_img"];

                            if (!empty($image_link)) {
                                $headers = @get_headers($image_link);
                                if ($headers && strpos($headers[0], "200")) {
                                    $imgHTML = '<img src="' . $image_link . '" alt="blank" loading="lazy" onerror="this.src=\'../images/default-image.png\'"/>';
                                }
                            }

                            $post_date  = DateTime::createFromFormat("Y-m-d H:i:s", $row["post_date"])->format("M d, Y");
                            $rawContent = isset($row["content"]) ? $row["content"] : '';

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
                                        <a class="post-img" href="single.php?id=<?php echo $row["post_id"]; ?>"><?php echo $imgHTML; ?></a>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="inner-content clearfix">
                                            <h3><a href="single.php?id=<?php echo $row["post_id"]; ?>"><?php echo $row["title"]; ?></a></h3>
                                            <div class="post-information">
                                                <span>
                                                    <i class="fa fa-tags" aria-hidden="true"></i>
                                                    <a href='category.php?cid=<?php echo $row["category"]; ?>'><?php echo $row["category_name"]; ?></a>
                                                </span>
                                                <span>
                                                    <i class="fa fa-user" aria-hidden="true"></i>
                                                    <a href='author.php?author=<?php echo $row["author"]; ?>'><?php echo $row["author"]; ?></a>
                                                </span>
                                                <span>
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                    <?php echo $post_date; ?>
                                                </span>
                                            </div>
                                            <p class="description"><?php echo $content; ?></p>
                                            <a class='read-more pull-right' href="single.php?id=<?php echo $row["post_id"]; ?>">Read More</a>
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
                    $page_sql    = "SELECT * FROM post WHERE post.title LIKE '%{$search_term}%'";
                    ($result1 = mysqli_query($conn, $page_sql)) or die("Query Failed");

                    if (mysqli_num_rows($result1) > 0) {
                        $total_records = mysqli_num_rows($result1);
                        $total_pages   = ceil($total_records / $limit);

                        echo "<ul class='pagination admin-pagination'>";

                        if ($page > 1) {
                            echo '<li><a href="search.php?search=' . $search_term . '&page=' . ($page - 1) . '">Prev</a></li>';
                        }

                        for ($i = 1; $i <= $total_pages; $i++) {
                            $active = ($i == $page) ? "active" : "";
                            echo '<li class="' . $active . '"><a href="search.php?search=' . $search_term . '&page=' . $i . '">' . $i . "</a></li>";
                        }

                        if ($total_pages > $page) {
                            echo '<li><a href="search.php?search=' . $search_term . '&page=' . ($page + 1) . '">Next</a></li>';
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