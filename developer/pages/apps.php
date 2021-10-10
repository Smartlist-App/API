<?php
session_start();
include("../../dashboard/cred.php");
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $conn->prepare("SELECT * FROM apps WHERE login_id=".$_SESSION['id']);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

$d = $stmt->fetchAll();
?>
<h5><b>Apps</b></h5>
<p>Manage your apps here. To create one, go to the "Login API" option in the sidebar</p>
<div class="row">
  <div class="col s12 m6">
    <table>
      <tr>
        <td><b>App Name</b></td>
        <td><b>Redirect URI</b></td>
        <td class="center"><b>ID</b></td>
      </tr>
      <?php
        foreach($d as $v) {
      ?>
      <tr>
        <td><?=$v['appName'];?></td>
        <td><?=$v['redirectURI'];?></td>
        <td><?=hash('sha512', $v['id']);?></td>
        <td onclick="loadPage('deleteApp.php?id='+<?=$v['id']?>)">Delete</td>
      </tr>
      <?php
        }
        $conn = null;
      ?>
    </table>
  </div>
</div>