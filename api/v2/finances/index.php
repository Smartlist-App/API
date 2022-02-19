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

if(!isset($_POST['token'])) {
    $data->error = "Invalid user token specified!";
    die(json_encode($data));
}
$data->error = null;
$data->success = true;
$userID = $d->fetchUserID($_POST['token']);


try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT * FROM Finances WHERE user = :id ORDER BY ID DESC");
    $sql->execute(array(
        ":id" => $userID
    ));
    $data->data = [];    
    $users = $sql->fetchAll();
    foreach($users as $row) {
        $obj = new stdClass();
        $obj->amount = intval(Encryption::decrypt($row['amount']));
        $obj->date = Encryption::decrypt($row['date']);
        $obj->spentOn = Encryption::decrypt($row['categories']);
        $data->data[] = $obj;
    }
}
catch (PDOException $e) {var_dump($e);}

echo json_encode($data);
?>