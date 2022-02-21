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
try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT * FROM Inventory WHERE user = :id ORDER BY ID DESC");
    $sql->execute([
        ":id" => UserID,
    ]);
    $users = $sql->fetchAll();
    foreach($users as $row) {
        if(
               str_contains(strtolower(Encryption::decrypt($row['name'])), strtolower($_POST['q']))
            || str_contains(strtolower(Encryption::decrypt($row['qty'])), strtolower($_POST['q']))
            || str_contains(strtolower((isset($row['note']) && !empty($row['note']) ? Encryption::decrypt($row['note']):"")), strtolower($_POST['q']))
            || str_contains(strtolower(Encryption::decrypt($row['category'])), strtolower($_POST['q']))
        ) {
            $obj = new stdClass();
            $obj->id = $row['id'];
            $obj->lastUpdated = $row['lastUpdated'];
            $obj->amount = Encryption::decrypt($row['qty']);
            $obj->sync = $row['user'] == $userID ? 0 : 1;
            $obj->title = Encryption::decrypt($row['name']);
            $obj->categories = Encryption::decrypt($row['category']);
            $obj->note = (isset($row['note']) && !empty($row['note']) ? Encryption::decrypt($row['note']):"");
            $obj->star = $row['star'];
            $obj->room = $row['room'];
            $data->data[] = $obj;
        }
    }
}
catch (PDOException $e) {API::error($e);}

API::output($data);