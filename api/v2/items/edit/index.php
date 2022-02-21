<?php
require '/home/smartlist/domains/smartlist.tech/private_html/app/cred.php';
require '/home/smartlist/domains/smartlist.tech/private_html/app/encrypt.php';
require '/home/smartlist/domains/smartlist.tech/private_html/api/v2/header.php';

$data = new stdClass();
$data->data = null;
$data->error = null;

API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'id', 'name', 'qty', 'lastUpdated', 'category']);
define('UserID', API::fetchUserID($_POST['token']));

$data->success = true;
$data->data = [];

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("UPDATE ".(isset($_POST['customRoom']) ? "CustomRoomItems":"Inventory")." SET name=:name, qty=:qty, category=:category, lastUpdated=:lastUpdated WHERE id=:id AND user=:user");
    $sql->execute([
        ":name" => Encryption::encrypt($_POST['name']),
        ":qty" => Encryption::encrypt($_POST['qty']),
        ":lastUpdated" => $_POST['lastUpdated'],
        ":category" => Encryption::encrypt($_POST['category']),
        ":id" => intval($_POST['id']),
        ":user" => UserID
    ]);
    $data->data = "Updated item";
}
catch (PDOException $e) {API::error($e);}

API::output($data);