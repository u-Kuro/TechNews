<?php             
    ob_start();
    date_default_timezone_set('UTC');
    // Get the API key for newsapi
    $apiKey = getenv("NEWSAPI_KEY");
    // Set the endpoint URL and parameters
    $apiurl = 'https://newsapi.org/v2/everything';
    $sql0="SELECT * from category";
    $result0=mysqli_query($conn,$sql0) or die("Query Failed");
    $newArticlesCount = 0;
    $newArticleTitles = [];
    if(mysqli_num_rows($result0) > 0){
        while($row = mysqli_fetch_assoc($result0)) {
            $category = $row['category_name'];
            $query = $row['query'];
            $categoryNumber = $row['category_id'];
            $params = [
                'q' => $query,
                'sortBy' => 'popularity',
                'language' => 'en',
                'pageSize' => '50',
                'apiKey' => $apiKey
            ];
            // Build the query string
            $queryString = http_build_query($params);
            // Build the full URL
            $requestUrl = $apiurl . '?' . $queryString;
            // Make the API request
            $curl = curl_init();
            // Set the cURL options
            curl_setopt_array($curl, array(
                CURLOPT_URL => $requestUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_USERAGENT => "NewsAPI/1.0",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache"
                ),
            ));
            // Execute the cURL request
            $response = curl_exec($curl);
            // Check for cURL errors
            if (curl_errno($curl)) {
                echo 'Error: ' . curl_error($curl);
            }
            // Close the cURL session
            curl_close($curl);
            // Handle the response
            if ($response !== false) {
                $data = json_decode($response, true);
                if(!is_null($data)){
                    if($data['status']=="ok"&&$categoryNumber!==false){
                        $articles = $data['articles'];
                        foreach ($articles as $article) {
                            $title=mysqli_real_escape_string($conn,$article["title"]);
                            $url=mysqli_real_escape_string($conn,$article["url"]);
                            $sql="SELECT * from post WHERE title='{$title}' OR post_url='{$url}' LIMIT 1;";
                            $result=mysqli_query($conn,$sql) or die("Query Failed");    
                            if(mysqli_num_rows($result) <= 0){   
                                $description=mysqli_real_escape_string($conn,$article["description"]);
                                $category=$categoryNumber;
                                $post_date=date('Y-m-d H:i:s', strtotime(mysqli_real_escape_string($conn,$article['publishedAt'])));;
                                $author=mysqli_real_escape_string($conn,$article["author"]);
                                $post_img=mysqli_real_escape_string($conn,$article["urlToImage"]);
                                $content=mysqli_real_escape_string($conn,$article["content"]);
                                // Allow if all information below are not empty
                                $variables = array($title, $description, $content, $url);
                                $allValuesAreValid = array_reduce($variables, function ($carry, $var) {
                                    return $carry && isset($var) && is_string($var) && !empty($var);
                                }, true);
                                if ($allValuesAreValid) {
                                    $sql1="INSERT INTO post(title,description,category,post_date,author,post_img,content,post_url) 
                                        VALUES ('{$title}','{$description}','{$category}','{$post_date}','{$author}','{$post_img}','{$content}','{$url}');";
                                    mysqli_query($conn,$sql1);
                                    $sql2 = "UPDATE category SET post = post + 1 WHERE category_id = {$categoryNumber};";
                                    mysqli_query($conn,$sql2);
                                    // Add some article details to be sent to the users sms
                                    $newArticlesCount++;
                                    if($newArticlesCount<=3){
                                        array_push($newArticleTitles, $title);
                                    }
                                }
                            } else {
                                // Only to Alert most recent articles
                                // Allow if all information below are not empty
                                $description=mysqli_real_escape_string($conn,$article["description"]);
                                $content=mysqli_real_escape_string($conn,$article["content"]);
                                $variables = array($title, $description, $content, $url);
                                $allValuesAreValid = array_reduce($variables, function ($carry, $var) {
                                    return $carry && isset($var) && is_string($var) && !empty($var);
                                }, true);
                                if ($allValuesAreValid) {
                                    // Add some article details to be sent to the users sms
                                    $newArticlesCount++;
                                    if($newArticlesCount<=3){
                                        array_push($newArticleTitles, $title);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $smsAPIDir;
        if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
            // echo __DIR__; // check current directory in webserver
            $smsAPIDir = '/clicksend/clicksendapi.php';
        } else {
            $smsAPIDir = '\clicksend\clicksendapi.php';
        }
        // After all articles are sent
        // variable $newArticleTitles, $newArticlesCount
        require_once dirname(__DIR__).$smsAPIDir;
    }
    ob_flush();
?>