<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'title', 'start', 'end', 'type']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));
try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("INSERT INTO Meals (title, start, end, user, type) VALUES (:title, :start, :end, :user, :type)");
    $sql->execute([
        ":title" => $_POST['title'],
        ":start" => $_POST['start'],
        ":end" => $_POST['end'],
        ":user" => UserID,
        ":type" => $_POST['type'],
    ]);
    $data['data']['id'] = $dbh->lastInsertId();
}
catch (PDOException $e) {API::error($e);}

API::output($data);