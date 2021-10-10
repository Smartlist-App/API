<?php
include("../../../../dashboard/cred.php");
$dbname = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
  $key = str_replace("Bearer ", "", $_SERVER['HTTP_AUTHORIZATION']);
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM apps WHERE secret=".json_encode($key));
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $d = $stmt->fetchAll();
    if($stmt->rowCount() == 1) {
      include("../../ratelimit.php");
      try {
        $conn = new PDO("mysql:host=$servername;dbname=".$username."_smartlist_api", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT * FROM tokens WHERE app=".json_encode($_POST['token']));
        $stmt->execute();
        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rr = $stmt->fetchAll();
        foreach($rr as $v) {
          $name = ($v['name']);
          $email = ($v['email']);
          $user_avatar = ($v['user_avatar']);
          $userId = ($v['userID']);
        }
      } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
      }
      echo '{"success": true, "message": "", "name": '.json_encode($name).', "email": '.json_encode($email).', "user_avatar": '.json_encode($user_avatar).',"id": '.json_encode($userId).'}';

      $conn2 = new PDO("mysql:host=$servername;dbname=".$username."_smartlist_api", $username, $password);
      $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql2 = "DELETE FROM tokens WHERE app=".json_encode($_POST['token']);
      $conn->exec($sql2);
      $conn2 = null;
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