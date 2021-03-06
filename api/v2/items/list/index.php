<?php
require dirname($_SERVER['DOCUMENT_ROOT']) . '/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']) . '/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']) . '/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'room']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try {
   $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
   $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
   $sql = $dbh->prepare((!isset($_POST['custom-room']) ?
      "
    SELECT * FROM Inventory -- standard stuff
    WHERE user = :id " . (isset($_POST['limit']) ? "" : " AND room = :room ") . " AND trash = 0
    ORDER BY lastUpdated " . (isset($_POST['order']) && $_POST['order'] == "ASC" ? "ASC" : "DESC") . " -- this means highest number (most recent) first
    LIMIT :limit"
      : "SELECT * FROM CustomRoomItems WHERE user = :id AND parent = :room AND trash = 0 ORDER BY ID DESC"));
   if(!isset($_POST['custom-room'])) {
      $params = [
         ":id" => UserID,
         ":limit" => isset($_POST['limit']) ? $_POST['limit'] : 500
      ];
   }
   else {
      $params = [
         ":id" => UserID,
         $params[':room'] = $_POST['room']
      ];
   }
   !isset($_POST['limit']) && $params[':room'] = $_POST['room'];
   $sql->execute($params);
   $data['data'] = [];
   $users = $sql->fetchAll();
   foreach ($users as $row) {
      $data['data'][] = [
         "id" => $row['id'],
         "lastUpdated" => $row['lastUpdated'],
         "amount" => Encryption::decrypt($row['qty']),
         "sync" => $row['user'] == UserID ? 0 : 1,
         "title" => Encryption::decrypt($row['name']),
         "categories" => json_decode(Encryption::decrypt($row['category'])),
         "note" => (isset($row['note']) && !empty($row['note']) ? Encryption::decrypt($row['note']) : ""),
         "star" => $row['star'],
         "room" => $row['room']
      ];
   }
} catch (PDOException $e) {
   API::error($e);
}

API::output($data);
