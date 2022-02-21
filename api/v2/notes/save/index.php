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
    $sql = $dbh->prepare("UPDATE Notes SET title=:title, content=:content WHERE id=:id AND user=:user");
    $sql->execute(array(
        ":title" => Encryption::encrypt($_POST['title']),
        ":content" => Encryption::encrypt($_POST['content']),
        ":user" => UserID,
        ":id" => $_POST['id']
    ));
    $users = $sql->fetchAll();
    $data->data = "All changes are saved";
}
catch (PDOException $e) {API::error($e);}

API::output($data);