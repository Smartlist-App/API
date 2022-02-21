<?php
require '/home/smartlist/domains/smartlist.tech/private_html/app/cred.php';
require '/home/smartlist/domains/smartlist.tech/private_html/app/encrypt.php';
require '/home/smartlist/domains/smartlist.tech/private_html/api/v2/header.php';

$data = new stdClass();
$data->data = null;
$data->error = null;

API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'id', 'date']);
define('UserID', API::fetchUserID($_POST['token']));

$data->success = true;
$data->data = [];

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("UPDATE ".(isset($_POST['customRoom']) ? "CustomRoomItems":"Inventory")." SET star = star ^ 1, lastUpdated=:date WHERE user = :user AND id = :id");
    $sql->execute([
        ":user" => UserID,
        ":id" => $_POST['id'],
        ":date" => $_POST['date']
    ]);
}
catch (PDOException $e) {API::error($e);}

API::output($data)