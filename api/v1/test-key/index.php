<?php
include("/home/smartlis/public_html/dashboard/cred.php");
$dbname = "smartlis_api";
// var_dump($_POST);
// var_dump($_SERVER);
// echo $_SERVER["HTTP_AUTHORIZATION"];
if($_SERVER["REQUEST_METHOD"] == "POST") {
  $key = str_replace("Bearer ", "", $_SERVER['HTTP_AUTHORIZATION']);
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM api_keys WHERE apiKey=".json_encode($key));
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $d = $stmt->fetchAll();
    if($stmt->rowCount() >= 1) {
      include("../ratelimit.php");
      echo '{"success": true, "message": "API key exists! :D"}';
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
  echo '{"success": false, "message": "Must use POST for API", "methodCurrentlyUsed": "'.$_SERVER['REQUEST_METHOD'].'"}';
}
?>