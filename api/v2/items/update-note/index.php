<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'id', 'date', 'content']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("UPDATE ".(isset($_POST['customRoom']) ? "CustomRoomItems":"Inventory")." SET note=:content, lastUpdated=:lastUpdated WHERE id=:id AND user=:user");
    $sql->execute(array(
        ":content" => Encryption::encrypt($_POST['content']),
        ":lastUpdated" => $_POST['date'],
        ":id" => $_POST['id'],
        ":user" => UserID
    ));
    $data['data'] = "Updated item";
}
catch (PDOException $e) {API::error($e);}

API::output($data);