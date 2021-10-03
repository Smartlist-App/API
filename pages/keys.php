<?php
session_start();
include("../../dashboard/cred.php");
$dbname = "bcxkspna_smartlist_api";
?>
<button onclick="loadPage('./pages/addKey.php')" class="right btn blue-grey darken-3 waves-effect waves-light"><i class="material-icons left">add</i>Create</button>
<h5><b>API keys</b></h5>
<p>Manage your API keys</p>
<div class="row">
  <div class="col s12 m6">
    <table>
       <tr>
         <td><b>Name</b></td>
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
  </div>
</div>