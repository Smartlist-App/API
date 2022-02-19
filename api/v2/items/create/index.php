<?php
ini_set("display_errors", 1);
$data = new stdClass();
require '/home/smartlist/domains/smartlist.tech/private_html/app/cred.php';
require '/home/smartlist/domains/smartlist.tech/private_html/app/encrypt.php';
require '/home/smartlist/domains/smartlist.tech/private_html/api/v2/header.php';

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
if(!isset($_POST['name'])) { $data->error = "Name not specified"; die(json_encode($data)); }
if(!isset($_POST['qty'])) { $data->error = "Quantity not specified"; die(json_encode($data)); }
if(!isset($_POST['category'])) { $data->error = "Category not specified"; die(json_encode($data)); }
if(!isset($_POST['lastUpdated'])) { $data->error = "lastUpdated not specified"; die(json_encode($data)); }
if(!isset($_POST['room'])) { $data->error = "Room not specified"; die(json_encode($data)); }
$data->error = null;
$data->success = true;
$userID = $d->fetchUserID($_POST['token']);

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare((isset($_POST['parent']) ? 
    "INSERT INTO CustomRoomItems (name, qty, category, user, star, lastUpdated, parent, trash) VALUES (:name, :qty, :category, :user, 0, :lastUpdated, :parent, 0)":
    "INSERT INTO Inventory (name, qty, category, user, star, lastUpdated, room, trash) VALUES (:name, :qty, :category, :user, 0, :lastUpdated, :room, 0)"
    ));
    if(isset($_POST['parent'])) {
        $sql->execute(array(
            ":name" => $e->encrypt($_POST['name']),
            ":qty" => $e->encrypt($_POST['qty']),
            ":category" => $e->encrypt($_POST['category']),
            ":parent" => $_POST['parent'],
            ":lastUpdated" => $_POST['lastUpdated'],
            ":user" => $userID
        ));
    }
    else {
        $sql->execute(array(
            ":name" => $e->encrypt($_POST['name']),
            ":qty" => $e->encrypt($_POST['qty']),
            ":category" => $e->encrypt($_POST['category']),
            ":lastUpdated" => $_POST['lastUpdated'],
            ":room" => $_POST['room'],
            ":user" => $userID
        ));
    }
    $data->data = "Created \"".htmlspecialchars($_POST['name'])."\" in ".htmlspecialchars($_POST['room']);
    if(isset($_POST['parent'])) {
        $data->data = "Created item!";
    }
}
catch (PDOException $e) {var_dump($e);}

echo json_encode($data);
?>