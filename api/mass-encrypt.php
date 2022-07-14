<?php






// ATTENTION: THIS FILE IS FOR --------- MASS ENCRYPTING/DECRYPTING --------- DATA. DO NOT COMMENT LINES 14/15! 





die("403");
exit();
require '../app/cred.php';
require '../app/encrypt.php';

try {
   $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
   $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
   $sql = $dbh->prepare("SELECT * FROM Inventory");
   $sql->execute();
   $users = $sql->fetchAll();
   foreach ($users as $user) {
      $sql = $dbh->prepare("UPDATE Inventory SET category = :category WHERE id = :id");
      $sql->execute([":category" => Encryption::encrypt('[]'), ":id" => $user['id']]);
   }
} catch (PDOException $e) {
   API::error($e);
}
