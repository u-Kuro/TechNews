<?php
date_default_timezone_set("UTC");

$apiKey = urlencode(getenv("CLICKSEND_API_KEY"));
$sender = urlencode(getenv("CLICKSEND_SENDER"));

$AppWebLink = "https://technews-j6sa.onrender.com/";

if (getenv("IS_PROD")) {
    $cacheFile = "/clicksendapicache.json";
} else {
    $cacheFile = "\clicksendapicache.json";
}
$cacheFile = __DIR__ . $cacheFile;

$requestLimit      = 1;
$intervalTime      = 86400 / $requestLimit;
$file_not_existing = !file_exists($cacheFile);

// Create cache file if it does not exist
if ($file_not_existing) {
    $fp = fopen($cacheFile, "w");
    fclose($fp);
    chmod($cacheFile, 0666);
    file_put_contents($cacheFile, true);
}

$shouldRunAPI = false;
$manualUpdate = isset($manualUpdate) && $manualUpdate;

if ($manualUpdate || $file_not_existing || time() - @filemtime($cacheFile) > $intervalTime) {
    touch($cacheFile);

    $api_name = "clicksend";
    $sql      = "SELECT last_update FROM api_interval WHERE api_name = '{$api_name}'";
    $result   = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row         = mysqli_fetch_assoc($result);
        $last_update = $row["last_update"];
        $time_diff   = time() - strtotime($last_update);

        if ($manualUpdate || $time_diff >= $intervalTime) {
            $sql1 = "UPDATE api_interval
                     SET last_update = CONVERT_TZ(NOW(), @@session.time_zone, '+00:00')
                     WHERE api_name = '{$api_name}'";
            mysqli_query($conn, $sql1);
            $shouldRunAPI = true;
        }
    } else {
        $sql2 = "INSERT INTO api_interval (api_name, last_update)
                 VALUES ('{$api_name}', CONVERT_TZ(NOW(), @@session.time_zone, '+00:00'))";
        mysqli_query($conn, $sql2);
        $shouldRunAPI = true;
    }

    if ($shouldRunAPI) {
        // Select valid international format phone numbers for all normal users
        $validIntFormatNumbersRegx = '^\\\+(?:[0-9] ?){6,14}[0-9]$';
        $sql3 = "SELECT DISTINCT phone_number FROM user
                 WHERE phone_number IS NOT NULL
                   AND phone_number REGEXP '{$validIntFormatNumbersRegx}'
                   AND role = '0'
                 LIMIT 30;";
        $result1 = mysqli_query($conn, $sql3);

        if (mysqli_num_rows($result1) > 0) {
            $numbersArr = [];
            while ($row = mysqli_fetch_assoc($result1)) {
                $numbersArr[] = $row["phone_number"];
            }
            $numbers = implode(",", $numbersArr);

            $numArticles = count($newArticleTitles);
            $messageStr  = "New articles available on FutureTechNews:\n\n";

            for ($i = 0; $i < $numArticles; $i++) {
                $messageStr .= $i + 1 . '. "' . $newArticleTitles[$i] . "\"\n";
            }

            $otherArticlesLeft = ($newArticlesCount > 3) ? ($newArticlesCount - $numArticles) . " " : "";
            $messageStr       .= "\nRead " . $otherArticlesLeft . "more at " . $AppWebLink;
            $message           = $messageStr;

            $data = [
                "method"   => "http",
                "username" => $sender,
                "key"      => $apiKey,
                "to"       => $numbers,
                "message"  => $message,
            ];

            $ch = curl_init("https://api-mapper.clicksend.com/http/v2/send.php");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
        } else {
            $alertMessage = '?alertMessage=We\'re sorry, but there are no available phone numbers at this time. This may be because there are currently no users registered on the website or there are no valid international phone numbers. Please try again later or contact support for assistance.';
        }
    }
}
