<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare((isset($_POST['parent']) ? 
    "INSERT INTO CustomRoomItems (name, qty, category, user, star, lastUpdated, parent, trash) VALUES (:name, :qty, :category, :user, 0, :lastUpdated, :parent, 0)":
    "INSERT INTO Inventory (name, qty, category, user, star, lastUpdated, room, trash) VALUES (:name, :qty, :category, :user, 0, :lastUpdated, :room, 0)"
    ));
    if(isset($_POST['parent'])) {
        $sql->execute([
            ":name" => Encryption::encrypt($_POST['name']),
            ":qty" => Encryption::encrypt($_POST['qty']),
            ":category" => Encryption::encrypt($_POST['category']),
            ":parent" => $_POST['parent'],
            ":lastUpdated" => $_POST['lastUpdated'],
            ":user" => UserID
        ]);
    }
    else {
        $sql->execute([
            ":name" => Encryption::encrypt($_POST['name']),
            ":qty" => Encryption::encrypt($_POST['qty']),
            ":category" => Encryption::encrypt($_POST['category']),
            ":lastUpdated" => $_POST['lastUpdated'],
            ":room" => $_POST['room'],
            ":user" => UserID
        ]);
    }
    $data['data'] = "Created \"".htmlspecialchars($_POST['name'])."\" in ".htmlspecialchars($_POST['room']);
    if(isset($_POST['parent'])) {
        $data['data'] = "Created item!";
    }
}
catch (PDOException $e) {API::error($e);}

API::output($data);