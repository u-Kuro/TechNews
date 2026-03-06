<?php
ob_start();
date_default_timezone_set("UTC");

$apiKey    = getenv("NEWSAPI_KEY");
$apiurl    = "https://newsapi.org/v2/everything";
$sql0      = "SELECT * FROM category";
($result0   = mysqli_query($conn, $sql0)) or die("Query Failed");

$newArticlesCount  = 0;
$newArticleTitles  = [];

if (mysqli_num_rows($result0) > 0) {
    while ($row = mysqli_fetch_assoc($result0)) {
        $category       = $row["category_name"];
        $query          = $row["query"];
        $categoryNumber = $row["category_id"];

        $params = [
            "q"        => $query,
            "sortBy"   => "popularity",
            "language" => "en",
            "pageSize" => "50",
            "apiKey"   => $apiKey,
        ];

        $queryString = http_build_query($params);
        $requestUrl  = $apiurl . "?" . $queryString;

        // Make the API request via cURL
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $requestUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_USERAGENT      => "NewsAPI/1.0",
            CURLOPT_HTTPHEADER     => ["cache-control: no-cache"],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            echo "Error: " . curl_error($curl);
        }

        curl_close($curl);

        if ($response !== false) {
            $data = json_decode($response, true);

            if (!is_null($data)) {
                if ($data["status"] == "ok" && $categoryNumber !== false) {
                    $articles = $data["articles"];

                    foreach ($articles as $article) {
                        $title = mysqli_real_escape_string($conn, $article["title"]);
                        $url   = mysqli_real_escape_string($conn, $article["url"]);

                        $sql    = "SELECT * FROM post WHERE title = '{$title}' OR post_url = '{$url}' LIMIT 1;";
                        ($result = mysqli_query($conn, $sql)) or die("Query Failed");

                        if (mysqli_num_rows($result) <= 0) {
                            // New article — insert it
                            $description = mysqli_real_escape_string($conn, $article["description"]);
                            $category    = $categoryNumber;
                            $post_date   = date("Y-m-d H:i:s", strtotime(mysqli_real_escape_string($conn, $article["publishedAt"])));
                            $author      = mysqli_real_escape_string($conn, $article["author"]);
                            $post_img    = mysqli_real_escape_string($conn, $article["urlToImage"]);
                            $content     = mysqli_real_escape_string($conn, $article["content"]);

                            $variables        = [$title, $description, $content, $url];
                            $allValuesAreValid = array_reduce($variables, function ($carry, $var) {
                                return $carry && isset($var) && is_string($var) && !empty($var);
                            }, true);

                            if ($allValuesAreValid) {
                                $sql1 = "INSERT INTO post (title, description, category, post_date, author, post_img, content, post_url)
                                         VALUES ('{$title}', '{$description}', '{$category}', '{$post_date}', '{$author}', '{$post_img}', '{$content}', '{$url}');";
                                mysqli_query($conn, $sql1);

                                $sql2 = "UPDATE category SET post = post + 1 WHERE category_id = {$categoryNumber};";
                                mysqli_query($conn, $sql2);

                                $newArticlesCount++;
                                if ($newArticlesCount <= 3) {
                                    array_push($newArticleTitles, $title);
                                }
                            }
                        } else {
                            // Article already exists — still count it for SMS alert
                            $description = mysqli_real_escape_string($conn, $article["description"]);
                            $content     = mysqli_real_escape_string($conn, $article["content"]);

                            $variables        = [$title, $description, $content, $url];
                            $allValuesAreValid = array_reduce($variables, function ($carry, $var) {
                                return $carry && isset($var) && is_string($var) && !empty($var);
                            }, true);

                            if ($allValuesAreValid) {
                                $newArticlesCount++;
                                if ($newArticlesCount <= 3) {
                                    array_push($newArticleTitles, $title);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    if (getenv("IS_PROD")) {
        $smsAPIDir = "/clicksend/clicksendapi.php";
    } else {
        $smsAPIDir = "\clicksend\clicksendapi.php";
    }

    require_once dirname(__DIR__) . $smsAPIDir;
}

ob_flush();
