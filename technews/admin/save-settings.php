<?php
session_status() === PHP_SESSION_ACTIVE || session_start();
include "../config.php";

if (!isset($_SESSION["username"])) {
    header("Location: ../login.php");
    exit();
} elseif ($_SESSION["user_role"] == 0) {
    header("Location: ../user/home.php");
    exit();
}

$result = true;
$location = "settings.php";
if (isset($_POST["submit"])) {
    if (empty($_FILES["logo"]["name"])) {
        $file_name = $_POST["old_logo"];
    } else {
        $errors = [];

        $file_name = $_FILES["logo"]["name"];
        $file_size = $_FILES["logo"]["size"];
        $file_tmp = $_FILES["logo"]["tmp_name"];
        $file_type = $_FILES["logo"]["type"];
        $exp = explode(".", $file_name);
        $file_ext = end($exp);

        $extensions = ["jpeg", "jpg", "png"];

        if (in_array($file_ext, $extensions) === false) {
            $errors[] =
                "This extension file not allowed, Please choose a JPG or PNG file.";
        }

        if ($file_size > 2097152) {
            $errors[] = "File size must be 2mb or lower.";
        }

        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "images/" . $file_name);
        } else {
            print_r($errors);
            die();
        }
    }

    $sql = "UPDATE settings SET websitename='{$_POST["website_name"]}',logo='{$file_name}',footerdesc='{$_POST["footer_desc"]}'";

    $result = mysqli_query($conn, $sql);
} elseif (isset($_POST["send-sms"])) {
    // Update API interval
    $apis = ["newsapi", "clicksend"];
    foreach ($apis as $api) {
        $sql = "UPDATE api_interval SET last_update = NOW() WHERE api_name = '{$api}'";
        mysqli_query($conn, $sql);
    }
    if (getenv("IS_PROD")) {
        //echo __DIR__; // check current directory in webserver
        $cacheFiles = "/api/newsapi/newsAPIcache.json";
    } else {
        $cacheFile = '\api\newsapi\newsAPIcache.json';
    }
    $cacheFile = dirname(__DIR__) . $cacheFile;
    if (!file_exists($cacheFile)) {
        $fp = fopen($cacheFile, "w");
        fclose($fp);
        chmod($cacheFile, 0666); // Set file permissions to allow
        file_put_contents($cacheFile, true);
    }
    touch($cacheFile);
    $manualUpdate = true;
    // Call news update API followed by SMS api
    include "../api/newsapi/newsapi.php";
    if (isset($alertMessage)) {
        $location = $location . $alertMessage;
    } else {
        $location =
            $location .
            "?alertMessage=Users have been successfully notified with the latest news!";
    }
}

if ($result) {
    header("location: $location");
    exit();
} else {
    echo "Query Failed: Settings";
}
