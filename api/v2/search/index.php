<?php
$data = new stdClass();
require '/home/smartlist.ga/smartlist.tech/app/cred.php';
require '/home/smartlist.ga/api.smartlist.tech/v2/header.php';
require '/home/smartlist.ga/smartlist.tech/app/encrypt.php';
require '/home/smartlist.ga/smartlist.tech/app/userdata.php';

$data->error = "Cannot ".$_SERVER['REQUEST_METHOD']." ".__FILE__;
$data->data = null;
$data->success = false;
$data->method = $_SERVER['REQUEST_METHOD'];
if($_SERVER['REQUEST_METHOD'] !== "POST") die(json_encode($data));

$d = new APIVerification();

if(!isset($_POST['token'])) {
    $data->error = "Invalid user token specified!";
    die(json_encode($data));
}
if(!isset($_POST['q'])) {
    $data->error = "Query (q) not specified!";
    die(json_encode($data));
}
$data->error = null;
$data->success = true;
$userID = $d->fetchUserID($_POST['token']);

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT * FROM Inventory WHERE user = :id ORDER BY ID DESC");
    $sql->execute(array(
        ":id" => $userID,
    ));
    $data->data = [];    
    $users = $sql->fetchAll();
    foreach($users as $row) {
        $e = new Encryption();
        if(
               str_contains(strtolower($e->decrypt($row['name'])), strtolower($_POST['q']))
            || str_contains(strtolower($e->decrypt($row['qty'])), strtolower($_POST['q']))
            || str_contains(strtolower((isset($row['note']) && !empty($row['note']) ? $e->decrypt($row['note']):"")), strtolower($_POST['q']))
            || str_contains(strtolower($e->decrypt($row['category'])), strtolower($_POST['q']))
        ) {
            $obj = new stdClass();
            $obj->id = $row['id'];
            $obj->lastUpdated = $row['lastUpdated'];
            $obj->amount = $e->decrypt($row['qty']);
            $obj->sync = $row['user'] == $userID ? 0 : 1;
            $obj->title = $e->decrypt($row['name']);
            $obj->categories = $e->decrypt($row['category']);
            $obj->note = (isset($row['note']) && !empty($row['note']) ? $e->decrypt($row['note']):"");
            $obj->star = $row['star'];
            $obj->room = $row['room'];
            $data->data[] = $obj;
        }
    }
}
catch (PDOException $e) {var_dump($e);}

echo json_encode($data);
?>