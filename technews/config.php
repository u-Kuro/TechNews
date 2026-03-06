<?php
date_default_timezone_set("UTC");

if (getenv("IS_PROD")) {
    $hostname = getenv("MYSQL_HOST");
    $username = getenv("MYSQL_USER");
    $password = getenv("MYSQL_PASSWORD");
    $dbname   = getenv("MYSQL_DATABASE");
    $port     = getenv("MYSQL_PORT");

    // Create CA cert file from environment variable
    $caCertPath    = "/tmp/ca.pem";
    $caCertContent = getenv("MYSQL_CA_CERT");
    file_put_contents($caCertPath, $caCertContent);
    chmod($caCertPath, 0600);

    $cacheFile = "/api/newsapi/newsAPIcache.json";
} else {
    $hostname  = "localhost";
    $username  = "root";
    $password  = "";
    $dbname    = "technews";
    $port      = 3306;
    $cacheFile = '\api\newsapi\newsAPIcache.json';
}

$cacheFile = __DIR__ . $cacheFile;

// Connect to the database
if (getenv("IS_PROD")) {
    // Production: SSL connection with CA certificate
    $conn = mysqli_init();

    if (!$conn) {
        die("mysqli_init failed");
    }

    mysqli_ssl_set($conn, null, null, $caCertPath, null, null);

    if (!mysqli_real_connect($conn, $hostname, $username, $password, $dbname, $port, null, MYSQLI_CLIENT_SSL)) {
        die("Connection failed: " . mysqli_connect_error());
    }
} else {
    // Local: standard connection
    ($conn = mysqli_connect($hostname, $username, $password, $dbname))
        or die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

// newsapi: 100 requests / 5 categories = 20 per day
$requestLimit = 100 / 5;
$intervalTime = 86400 / $requestLimit;

$file_not_existing = !file_exists($cacheFile);

// Create cache file if it does not exist
if ($file_not_existing) {
    $fp = fopen($cacheFile, "w");
    fclose($fp);
    chmod($cacheFile, 0666);
    file_put_contents($cacheFile, true);
}

// Fetch new data if cache is missing or expired
if ($file_not_existing || time() - @filemtime($cacheFile) > $intervalTime) {
    touch($cacheFile);

    $api_name = "newsapi";
    $sql      = "SELECT last_update FROM api_interval WHERE api_name = '{$api_name}'";
    $result   = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row         = mysqli_fetch_assoc($result);
        $last_update = $row["last_update"];
        $time_diff   = time() - strtotime($last_update);

        if ($time_diff >= $intervalTime) {
            $sql = "UPDATE api_interval
                    SET last_update = CONVERT_TZ(NOW(), @@session.time_zone, '+00:00')
                    WHERE api_name = '{$api_name}'";
            mysqli_query($conn, $sql);
            require_once "api/newsapi/newsapi.php";
        }
    } else {
        $sql = "INSERT INTO api_interval (api_name, last_update)
                VALUES ('{$api_name}', CONVERT_TZ(NOW(), @@session.time_zone, '+00:00'))";
        mysqli_query($conn, $sql);
        require_once "api/newsapi/newsapi.php";
    }
}
