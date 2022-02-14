<?php
ini_set("display_errors", 1);

$data = new stdClass();
require '/home/smartlist.ga/smartlist.tech/app/cred.php';
require '/home/smartlist.ga/api.smartlist.tech/v2/header.php';
require '/home/smartlist.ga/smartlist.tech/app/encrypt.php';
require '/home/smartlist.ga/smartlist.tech/app/userdata.php';
$e = new Encryption();

$data->error = "Cannot ".$_SERVER['REQUEST_METHOD']." ".__FILE__;
$data->data = null;
$data->success = false;
$data->method = $_SERVER['REQUEST_METHOD'];
if($_SERVER['REQUEST_METHOD'] !== "POST") die(json_encode($data));

$d = new APIVerification();

if(!isset($_POST['token'])) { $data->error = "Invalid user token specified!"; die(json_encode($data)); }
if(!isset($_POST['start'])) { $data->error = "start not specified!"; die(json_encode($data)); }
if(!isset($_POST['end'])) { $data->error = "end not specified!"; die(json_encode($data)); }
if(!isset($_POST['type'])) { $data->error = "type not specified!"; die(json_encode($data)); }
if(!isset($_POST['title'])) { $data->error = "title not specified!"; die(json_encode($data)); }
$data->error = null;
$data->success = true;
$userID = $d->fetchUserID($_POST['token']);

$data->data = null;

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("INSERT INTO Meals (title, start, end, user, type) VALUES (:title, :start, :end, :user, :type)");
    $sql->execute(array(
        ":title" => $_POST['title'],
        ":start" => $_POST['start'],
        ":end" => $_POST['end'],
        ":user" => $userID,
        ":type" => $_POST['type'],
    ));
}
catch (PDOException $e) {var_dump($e);}

echo json_encode($data);
?>