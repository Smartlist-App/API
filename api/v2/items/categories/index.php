<?php
$data = new stdClass();
require '/home/smartlist/domains/smartlist.tech/private_html/app/cred.php';
require '/home/smartlist/domains/smartlist.tech/private_html/app/encrypt.php';
require '/home/smartlist/domains/smartlist.tech/private_html/api/v2/header.php';

$data->error = "Cannot ".$_SERVER['REQUEST_METHOD']." ".__FILE__;
$data->data = null;
$data->success = false;
$data->method = $_SERVER['REQUEST_METHOD'];
if($_SERVER['REQUEST_METHOD'] !== "POST") die(json_encode($data));

APIVerification::requireParams(['token']);

$data->error = null;
$data->success = true;
$userID = APIVerification::fetchUserID($_POST['token']);
 
try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT `name`, `category`, `id`, `user` FROM Inventory WHERE user = :id ORDER BY ID DESC");
    $sql->execute(array(
        ":id" => $userID
    ));
    $data->data = [];    
    $users = $sql->fetchAll();
    foreach($users as $row) {
        $obj = new stdClass();
        $obj->id = $row['id'];
        $obj->title = Encryption::decrypt($row['name']);
        $obj->categories = Encryption::decrypt($row['category']);
        $data->data[] = $obj;
    }
    $data->data = returnUniqueProperty($data->data,'categories');
}
catch (PDOException $e) {var_dump($e);}

echo json_encode($data);
