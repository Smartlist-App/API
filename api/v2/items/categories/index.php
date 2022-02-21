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
    $sql = $dbh->prepare("SELECT `category`, `id` FROM Inventory WHERE user = :id ORDER BY ID DESC");
    $sql->execute(array(
        ":id" => UserID
    ));
    $data->data = [];    
    $users = $sql->fetchAll();
    foreach($users as $row) {
        $obj = new stdClass();
        $obj->id = $row['id'];
        $obj->categories = Encryption::decrypt($row['category']);
        $data->data[] = $obj;
    }
    $data->data = returnUniqueProperty($data->data,'categories');
}
catch (PDOException $e) {var_dump($e);}

echo json_encode($data);
