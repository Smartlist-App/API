<?php
session_start();
include('../dashboard/cred.php');
$dbname = "";
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // sql to delete a record
  $sql = "DELETE FROM apps WHERE id=".$_GET['id']. " AND login_id=".$_SESSION['id'];

  // use exec() because no results are returned
  $conn->exec($sql);
  echo "App deleted successfully";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>