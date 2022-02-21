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

$data->error = null;
$data->success = true;

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare((isset($_POST['customRoom']) ? 
    (isset($_POST['forever'])  ? "DELETE FROM CustomRoomItems WHERE id = :id AND user = :user" : "UPDATE CustomRoomItems SET trash = trash ^ 1, lastUpdated=:date WHERE user = :user AND id = :id" ) : 
    (isset($_POST['forever'])  ? "DELETE FROM Inventory WHERE id = :id AND user = :user" : "UPDATE Inventory SET trash = trash ^ 1, lastUpdated=:date WHERE user = :user AND id = :id" )
    ));
    $sql->execute(isset($_POST['forever']) ? [
        ":user" => UserID,
        ":id" => $_POST['id']
    ] : [
        ":user" => UserID,
        ":id" => $_POST['id'],
        ":date" => $_POST['date']
    ]);
}
catch (PDOException $e) {API::error($e);}

API::outpit($data);