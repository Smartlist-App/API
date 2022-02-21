<?php
require '/home/smartlist/domains/smartlist.tech/private_html/app/cred.php';
require '/home/smartlist/domains/smartlist.tech/private_html/app/encrypt.php';
require '/home/smartlist/domains/smartlist.tech/private_html/api/v2/header.php';

$data = new stdClass();
$data->data = null;
$data->error = null;

API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'id']);
define('UserID', API::fetchUserID($_POST['token']));

$data->success = true;
$data->data = [];
try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("DELETE FROM Meals WHERE id = :id AND user = :user");
    $sql->execute(array(
        ":user" => UserID,
        ":id" => $_POST['id']
    ));
}
catch (PDOException $e) {API::error($e);}

echo API::output($data);