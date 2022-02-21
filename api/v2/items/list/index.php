<?php
ini_set("display_errors", 1);
require '/home/smartlist/domains/smartlist.tech/private_html/app/cred.php';
require '/home/smartlist/domains/smartlist.tech/private_html/app/encrypt.php';
require '/home/smartlist/domains/smartlist.tech/private_html/api/v2/header.php';

$data = new stdClass();
$data->data = null;
$data->error = null;

API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'room']);
define('UserID', API::fetchUserID($_POST['token']));

$data->success = true;
$data->data = [];

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare((!isset($_POST['custom-room']) ? 
    "SELECT * FROM Inventory WHERE user = :id AND room = :room AND trash = 0 ORDER BY ID DESC":
    "SELECT * FROM CustomRoomItems WHERE user = :id AND parent = :room AND trash = 0 ORDER BY ID DESC"
    ));
    $sql->execute([
        ":id" => UserID,
        ":room" => $_POST['room'],
    ]);
    $data->data = [];    
    $users = $sql->fetchAll();
    foreach($users as $row) {
        $data->data[] = [
            "id" => $row['id'],
            "lastUpdated" => $row['lastUpdated'],
            "amount" => Encryption::decrypt($row['qty']),
            "sync" => $row['user'] == UserID ? 0 : 1,
            "title" => Encryption::decrypt($row['name']),
            "categories" => Encryption::decrypt($row['category']),
            "note" => (isset($row['note']) && !empty($row['note']) ? Encryption::decrypt($row['note']):""),
            "star" => $row['star']
        ];
    }
}
catch (PDOException $e) {API::error($e);}

API::output($data);