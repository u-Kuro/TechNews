<?php
// Set the database hostname, username, password, and name
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "technews";

// Connect to the database
$conn = mysqli_connect($hostname, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
// require_once 'api/newsapi/newsapi.php';
// Set the cache file path, request limit, and interval time
$cacheFile = __DIR__ . '\api\newsapi\newsAPIcache.json';
$requestLimit = 100/5; // Current newsapi limit is 100 divided by 5 different request
$intervalTime = 86400 / $requestLimit; // Interval request for 24 hours (in seconds)

$file_not_existing = !file_exists($cacheFile);
// Create cache file if it does not exist
if ($file_not_existing) {
    $fp = fopen($cacheFile, 'w');
    fclose($fp);
    chmod($cacheFile, 0666); // Set file permissions to allow read/write access
    file_put_contents($cacheFile, true);
}
// Run if cache does not exists and has not expired
if ($file_not_existing || time() - @filemtime($cacheFile) > $intervalTime) {
    // Add cache file and set the file modification time to now   
    touch($cacheFile);
    // Cache does not exist or has expired, fetch new data
    $api_name = 'newsapi';
    $sql = "SELECT last_update FROM api_interval WHERE api_name = '{$api_name}'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_update = $row['last_update'];
        // Calculate the time difference between the last update time and the current time
        $time_diff = time() - strtotime($last_update); // Time difference (in seconds)
        if ($time_diff >= $intervalTime) {
            // Update the last update time for the API in the database
            $sql = "UPDATE api_interval SET last_update = NOW() WHERE api_name = '{$api_name}'";
            mysqli_query($conn, $sql);
            // Retrieve new data from the API
            require_once 'api/newsapi/newsapi.php';
        }
    } else {
        // Insert a new record for the API if it doesn't exist in the table
        $sql = "INSERT INTO api_interval (api_name, last_update) VALUES ('$api_name', NOW())";
        mysqli_query($conn, $sql);
        require_once 'api/newsapi/newsapi.php';
    }
}
?>
