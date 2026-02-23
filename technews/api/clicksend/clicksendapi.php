<?php
    date_default_timezone_set('UTC');
    $apiKey = urlencode(getenv("CLICKSEND_API_KEY"));
    $sender = urlencode(getenv("CLICKSEND_SENDER"));

    ////
    $AppWebLink = 'https://futuretechnews.rf.gd/';
    if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
          // Code to run if the script is not running on the localhost
        // echo __DIR__; // check current directory in webserver
        $cacheFile = '/clicksendapicache.json';
    } else {
        $cacheFile = '\clicksendapicache.json';
    }
    $cacheFile = __DIR__ . $cacheFile;
    $requestLimit = 1; // Current clicksend api limit is just 4 numbers at most
    $intervalTime = 86400 / $requestLimit; // Interval request for 24 hours (in seconds)
    $file_not_existing = !file_exists($cacheFile);
    // Create cache file if it does not exist
    if ($file_not_existing) {
        $fp = fopen($cacheFile, 'w');
        fclose($fp);
        chmod($cacheFile, 0666); // Set file permissions to allow read/write access
        file_put_contents($cacheFile, true);
    }
    // Will check if it will run the api
    $shouldRunAPI = false;
    // Run if cache does not exists and has not expired
    $manualUpdate = isset($manualUpdate) && $manualUpdate; // from /api/save-settings.php manual update
    if ($manualUpdate || $file_not_existing || time() - @filemtime($cacheFile) > $intervalTime) {
        // Add cache file and set the file modification time to now   
        touch($cacheFile);
        // Cache does not exist or has expired, fetch new data
        $api_name = 'clicksend';
        $sql = "SELECT last_update FROM api_interval WHERE api_name = '{$api_name}'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $last_update = $row['last_update'];
            // Calculate the time difference between the last update time and the current time
            $time_diff = time() - strtotime($last_update); // Time difference (in seconds)
            if ($manualUpdate || $time_diff >= $intervalTime) {
                // Update the last update time for the API in the database
                $sql1 = "UPDATE api_interval SET last_update = CONVERT_TZ(NOW(), @@session.time_zone, '+00:00') WHERE api_name = '{$api_name}'";
                mysqli_query($conn, $sql1);
                $shouldRunAPI = true;
            }
        } else {
            // Insert a new record for the API if it doesn't exist in the table
            $sql2 = "INSERT INTO api_interval (api_name, last_update) VALUES ('$api_name', CONVERT_TZ(NOW(), @@session.time_zone, '+00:00'))";
            mysqli_query($conn, $sql2);
            $shouldRunAPI = true;
        }
        
        if($shouldRunAPI){
            // variable from /newsapi/newsapi.php: $newArticleTitles, $newArticlesCount
            // Account details	
            // Select valid international format phone numbers for technews api
            $validIntFormatNumbersRegx = '^\\\+(?:[0-9] ?){6,14}[0-9]$';
            $sql3 = "SELECT DISTINCT phone_number FROM user WHERE phone_number 
                    IS NOT NULL 
                    AND phone_number REGEXP '{$validIntFormatNumbersRegx}'
                    AND role = '0'
                    LIMIT 30;";
            $result1 = mysqli_query($conn, $sql3);
            if (mysqli_num_rows($result1) > 0) {
                $numbersArr = array();
                while ($row = mysqli_fetch_assoc($result1)) {
                    $numbersArr[] = $row['phone_number'];
                }
                $numbers = implode(',', $numbersArr);

                $numArticles = count($newArticleTitles);
                // Prepare message
                $messageStr = "New articles available on FutureTechNews:\n\n";
                for ($i = 0; $i < $numArticles; $i++) {
                    $messageStr .= ($i+1).'. "' . $newArticleTitles[$i] . "\"\n";
                }
                $otherArticlesLeft = '';
                if($newArticlesCount>3){
                    $otherArticlesLeft = ($newArticlesCount-$numArticles).' ';
                }
                $messageStr .= "\nRead ".$otherArticlesLeft."more at " . $AppWebLink;
                $message = $messageStr;
            
                // Prepare data for POST request (numbers is limited to 10000)
                $data = array('method' => 'http', 'username' => $sender, 'key' => $apiKey, 'to' => $numbers, "message" => $message);
                // Send the POST request with cURL
                $ch = curl_init('https://api-mapper.clicksend.com/http/v2/send.php');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
            } else {
                // for save-settings.php 
                $alertMessage = '?alertMessage=We\'re sorry, but there are no available phone numbers at this time. This may be because there are currently no users registered on the website or there are no valid international phone numbers. Please try again later or contact support for assistance.';
            }
        }
    }
	
?>