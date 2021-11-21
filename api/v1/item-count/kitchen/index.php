<?php
include("/home/smartlis/public_html/dashboard/cred.php");
$dbname = "smartlis_api";
if($_SERVER["REQUEST_METHOD"] == "POST") {
  $key = str_replace("Bearer ", "", $_SERVER['HTTP_AUTHORIZATION']);
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM api_keys WHERE apiKey=:key");
    $stmt->execute(array(":key" => $key));
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $d = $stmt->fetchAll();
    if($stmt->rowCount() == 1) {
      include("../../ratelimit.php");
      $dbname = "smartlis_inv";
      
      $conn1 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $stmt1 = $conn1->prepare("SELECT * FROM products");
      $stmt1->execute();
      $result1 = $stmt1->setFetchMode(PDO::FETCH_ASSOC);
      $res = $stmt1->fetchAll();
      echo '{"success": true, "message": "", "itemCount": '.$stmt1->rowCount().'}';

    }
    else {
      echo '{"success": false, "message": "API doesn\'t exist :(  - Make sure your API token is valid, and you are using Bearer to authorize your token"}';
    }
  } catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
  $conn = null;
}
else {
  echo '{"success": false, "message": "Must use POST for API"}';
}
?>