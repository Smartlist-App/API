<?php
require '/home/smartlist/domains/smartlist.tech/private_html/app/cred.php';
require '/home/smartlist/domains/smartlist.tech/private_html/app/encrypt.php';
require '/home/smartlist/domains/smartlist.tech/private_html/api/v2/header.php';

$data = new stdClass();
$data->data = null;
$data->error = null;

API::allowRequestMethods(["POST"]);
API::requireParams(['token']);
define('UserID', API::fetchUserID($_POST['token']));

$data->success = true;
$data->data = [];

$data->success = true;

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT * FROM Inventory WHERE user = :id AND star = 1 AND trash = 0");
    $sql->execute(array(
        ":id" => UserID
    ));
    $data->data = [];    
    $users = $sql->fetchAll();
    foreach($users as $row) {
        $obj = new stdClass();
        $obj->id = $row['id'];
        $obj->lastUpdated = $row['lastUpdated'];
        $obj->amount = Encryption::decrypt($row['qty']);
        $obj->sync = $row['user'] == UserID ? 0 : 1;
        $obj->title = Encryption::decrypt($row['name']);
        $obj->room = $row['room'];
        $obj->note = Encryption::decrypt($row['note']);
        $obj->categories = Encryption::decrypt($row['category']);
        $obj->star = $row['star'];
        $data->data[] = $obj;
    }
}
catch (PDOException $e) {API::error($e);}

API::output($data);