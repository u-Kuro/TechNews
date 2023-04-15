<?php
  $hostname= "technews";
  $conn=mysqli_connect("localhost","root","","technews") or die("connection failed ". mysqli_connect_error());
  // Interval Update for NEWSAPI
  $api_name = 'newsapi';
  $sql = "SELECT last_update FROM api_interval WHERE api_name = '{$api_name}'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $last_update = $row['last_update'];
      // Calculate the time difference between the last update time and the current time
      $time_diff = time() - strtotime($last_update);
      $requestLimit = 100/5; // Current newsapi Limit is 100 divided by 5 different request
      $intervalTime = 86400 / $requestLimit; // interval request for 24 hrs (86400 s)
      if ($time_diff >= $intervalTime) {
          // Update the last update time for the API in the database
          $sql = "UPDATE api_interval SET last_update = NOW() WHERE api_name = '{$api_name}'";
          mysqli_query($conn, $sql);
          // Retrieve new data from the API
          require_once 'api/newsapi.php';
      }
  } else {
      // Insert a new record for the API if it doesn't exist in the table
      $sql = "INSERT INTO api_interval (api_name, last_update) VALUES ('$api_name', NOW())";
      mysqli_query($conn, $sql);
      require_once 'api/newsapi.php';
  }

?>
