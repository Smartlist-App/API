<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));
$data['data'] = [];
try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT * FROM Inventory WHERE user = :user AND trash = 1 ORDER BY ID ASC");
    $sql->execute([
        ":user" => UserID
    ]);
    $users = $sql->fetchAll();
    foreach($users as $row) {
        $obj = new stdClass();
        $obj->id = intval($row['id']);
        $obj->title = Encryption::decrypt($row['name']);
        $obj->amount = Encryption::decrypt($row['qty']);
        $obj->room = $row['room'];
        $data['data'][] = $obj;
    }
}
catch (PDOException $e) {API::error($e);}

API::output($data);