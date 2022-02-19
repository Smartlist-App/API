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
if(!isset($_POST['id'])) {
    $data->error = "ID not specified!";
    die(json_encode($data));
}
$data->error = null;
$data->success = true;
$userID = $d->fetchUserID($_POST['token']);


try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT * FROM Notes WHERE user = :user and id=:id ORDER BY ID ASC");
    $sql->execute(array(
        ":user" => $userID,
        ":id" => $_POST['id']
    ));
    $users = $sql->fetchAll();
    foreach($users as $row) {
        $e = new Encryption();
        $obj = new stdClass();
        $obj->id = intval($row['id']);
        $obj->title = Encryption::decrypt($row['title']);
        $obj->banner = $row['banner'] == "" ? "" : Encryption::decrypt($row['banner']);
        $obj->content = json_decode(Encryption::decrypt($row['content']));
        $data->data = $obj;
    }
}
catch (PDOException $e) {var_dump($e);}

echo json_encode($data);
?>