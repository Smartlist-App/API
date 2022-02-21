<?php
require '/home/smartlist/domains/smartlist.tech/private_html/app/cred.php';
require '/home/smartlist/domains/smartlist.tech/private_html/app/encrypt.php';
require '/home/smartlist/domains/smartlist.tech/private_html/api/v2/header.php';

$data = new stdClass();
$data->data = null;
$data->error = null;

API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'name', 'qty', 'category', 'lastUpdated', 'room']);
define('UserID', API::fetchUserID($_POST['token']));

$data->success = true;
$data->data = [];

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare((isset($_POST['parent']) ? 
    "INSERT INTO CustomRoomItems (name, qty, category, user, star, lastUpdated, parent, trash) VALUES (:name, :qty, :category, :user, 0, :lastUpdated, :parent, 0)":
    "INSERT INTO Inventory (name, qty, category, user, star, lastUpdated, room, trash) VALUES (:name, :qty, :category, :user, 0, :lastUpdated, :room, 0)"
    ));
    if(isset($_POST['parent'])) {
        $sql->execute(array(
            ":name" => Encryption::encrypt($_POST['name']),
            ":qty" => Encryption::encrypt($_POST['qty']),
            ":category" => Encryption::encrypt($_POST['category']),
            ":parent" => $_POST['parent'],
            ":lastUpdated" => $_POST['lastUpdated'],
            ":user" => UserID
        ));
    }
    else {
        $sql->execute(array(
            ":name" => Encryption::encrypt($_POST['name']),
            ":qty" => Encryption::encrypt($_POST['qty']),
            ":category" => Encryption::encrypt($_POST['category']),
            ":lastUpdated" => $_POST['lastUpdated'],
            ":room" => $_POST['room'],
            ":user" => UserID
        ));
    }
    $data->data = "Created \"".htmlspecialchars($_POST['name'])."\" in ".htmlspecialchars($_POST['room']);
    if(isset($_POST['parent'])) {
        $data->data = "Created item!";
    }
}
catch (PDOException $e) {var_dump($e);}

echo json_encode($data);
?>