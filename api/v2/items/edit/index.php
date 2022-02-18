<?php
ini_set("display_errors", 1);
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
$e = new Encryption();

if(!isset($_POST['token'])) {
    $data->error = "Invalid user token specified!";
    die(json_encode($data));
}
if(!isset($_POST['id'])) { $data->error = "ID not specified"; die(json_encode($data)); }
if(!isset($_POST['name'])) { $data->error = "Name not specified"; die(json_encode($data)); }
if(!isset($_POST['qty'])) { $data->error = "Quantity not specified"; die(json_encode($data)); }
if(!isset($_POST['lastUpdated'])) { $data->error = "lastUpdated not specified"; die(json_encode($data)); }
if(!isset($_POST['category'])) { $data->error = "Categories not specified"; die(json_encode($data)); }
$data->error = null;
$data->success = true;
$userID = $d->fetchUserID($_POST['token']);

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("UPDATE ".(isset($_POST['customRoom']) ? "CustomRoomItems":"Inventory")." SET name=:name, qty=:qty, category=:category, lastUpdated=:lastUpdated WHERE id=:id AND user=:user");
    $sql->execute(array(
        ":name" => $e->encrypt($_POST['name']),
        ":qty" => $e->encrypt($_POST['qty']),
        ":lastUpdated" => $_POST['lastUpdated'],
        ":category" => $e->encrypt($_POST['category']),
        ":id" => $_POST['id'],
        ":user" => $userID
    ));
    $data->data = "Updated item";
}
catch (PDOException $e) {var_dump($e);}

echo json_encode($data);
?>