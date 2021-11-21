<?php
require("/home/smartlis/public_html/dashboard/cred.php");
$dbname = "smartlis_api";
if($_SERVER["REQUEST_METHOD"] == "POST") {
  $key = str_replace("Bearer ", "", $_SERVER['HTTP_AUTHORIZATION']);
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM apps WHERE secret=:key");
    $stmt->execute(array(":key" => $key));
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $d = $stmt->fetchAll();
    if($stmt->rowCount() == 1) {
      // include("../../ratelimit.php");
      try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT * FROM tokens WHERE app=:token");
        $stmt->execute(array(":token" => $_POST['token']));
        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rr = $stmt->fetchAll();
        foreach($rr as $v) {
          $name = $v['name'];
          $email = $v['email'];
          $user_avatar = $v['user_avatar'];
          $userId = $v['userID'];
        }
      } catch(PDOException $e) {
        echo "Error message1: " . $e->getMessage();
      }
      echo '{"success": true, "message": "", "name": '.json_encode($name).', "email": '.json_encode($email).', "user_avatar": '.json_encode($user_avatar).',"id": '.json_encode($userId).'}';

      $stmt = $conn->prepare("DELETE FROM tokens WHERE app=:token");
      $stmt->execute( array(":token" => $_POST['token']) );
    }
    else {
      echo '{"success": false, "message": "API doesn\'t exist :(  - Make sure your API token is valid, and you are using Bearer to authorize your token"}';
    }
  } catch(PDOException $e) {
    echo "Error message2: " . $e->getMessage();
  }
  $conn = null;
}
else {
  echo '{"success": false, "message": "Must use POST for API"}';
}
?>