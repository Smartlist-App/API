<?php
session_start();
include("../../dashboard/cred.php");
?>
<table>
  <tr>
    <td><b>Name</b></td>
    <td><b>Usage</b></td>
    <td class="center"><b>API Key</b></td>
  </tr>
  <?php
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM api_keys WHERE login_id=".$_SESSION['id']);
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $d = $stmt->fetchAll();
    foreach($d as $v) {
  ?>
  <tr>
    <td><?=$v['name'];?></td>
    <td><?=$v['ratelimit'];?>/500</td>
    <td><?=$v['apiKey'];?></td>
    <td><a class="del" href="#" onclick='$("#app").load("./pages/delKey.php?id=<?=$v['id'];?>")'>Delete</a></td>
  </tr>
  <?php
    }
  } catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
  $conn = null;
  ?>
</table>