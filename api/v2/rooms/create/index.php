<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'name']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));
try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("INSERT INTO Rooms (name, user) VALUES (:name, :user)");
    $sql->execute([
        ":user" => UserID,
        ":name" => $_POST['name'],
    ]);
    $data['data']['name'] = $_POST['name'];
    $data['data']['id'] = $dbh->lastInsertId();
}
catch (PDOException $e) {API::error($e);}

API::output($data);