<?php
include("/home/smartlis/public_html/dashboard/cred.php");
$dbname = "smartlis_api";
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $conn->prepare("SELECT * FROM `recipes`");
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  $res = $stmt->fetchAll();
  var_dump($res);
  // foreach(new TableRows($res as $k=>$v) {
  //   echo $v;
  // }
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;
?>