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
    $sql = $dbh->prepare("SELECT * FROM Notes WHERE user = :user and id=:id ORDER BY ID ASC");
    $sql->execute([
        ":user" => UserID,
        ":id" => $_POST['id']
    ]);
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
catch (PDOException $e) {API::error($e);}

API::output($data);