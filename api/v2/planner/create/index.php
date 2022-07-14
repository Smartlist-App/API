<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'startDate', 'endDate', 'type', 'title', 'field1']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("INSERT INTO Planner (startDate, endDate, type, title, field1, user) VALUES (:startDate, :endDate, :type, :title, :field1, :user)");
    $sql->execute([
        ":startDate" => Encryption::encrypt($_POST['startDate']),
        ":endDate" => Encryption::encrypt($_POST['endDate']),
        ":type" => Encryption::encrypt($_POST['type']),
        ":title" => Encryption::encrypt($_POST['title']),
        ":field1" => Encryption::encrypt($_POST['field1']),
        ":user" => UserID
    ]);
    $data['data'] = [
        'id' => $dbh->lastInsertId(),
        'startDate' => $_POST['startDate'],
        'endDate' => $_POST['endDate'],
        'type' => $_POST['type'],
        'title' => $_POST['title'],
        'field1' => $_POST['field1']
    ];
}
catch (PDOException $e) {API::error($e);}

API::output($data);
