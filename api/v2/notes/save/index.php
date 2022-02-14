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

if(!isset($_POST['token'])) {
    $data->error = "Invalid user token specified!";
    die(json_encode($data));
}
if(!isset($_POST['id'])) {
    $data->error = "ID not specified!";
    die(json_encode($data));
}
$data->error = null;
$data->success = true;
$userID = $d->fetchUserID($_POST['token']);

$e = new Encryption();

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("UPDATE Notes SET title=:title, content=:content WHERE id=:id AND user=:user");
    $sql->execute(array(
        ":title" => $e->encrypt($_POST['title']),
        ":content" => $e->encrypt($_POST['content']),
        ":user" => $userID,
        ":id" => $_POST['id']
    ));
    $users = $sql->fetchAll();
    $data->data = "All changes are saved";
}
catch (PDOException $e) {var_dump($e);}

echo json_encode($data);
?>