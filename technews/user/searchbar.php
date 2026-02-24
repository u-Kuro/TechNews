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
<!-- search box -->
<div class="search-box-container">
    <h4>Search</h4>
    <form class="search-post" action="search.php" method="GET">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-danger">Search</button>
            </span>
        </div>
    </form>
</div>
<!-- /search box -->