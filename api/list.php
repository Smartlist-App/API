<?php
// phpinfo();
// die("403");
ini_set("display_errors", 1);
require "../app/cred.php";
require "../app/encrypt.php";

$dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$sql = $dbh->prepare("SELECT * FROM Accounts ORDER BY id DESC");
$sql->execute();
$users = $sql->fetchAll();
$found = false;
echo hash('whirlpool', md5(326));
?>
<table>
   <?php foreach ($users as $row) { ?>
      <tr>
         <td><?= $row['id'] ?></td>
         <td><?= Encryption::decrypt($row['email']) ?></td>
      </tr>
   <?php } ?>
</table>