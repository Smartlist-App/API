<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'start', 'end']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("UPDATE Meals SET start = :start, end = :end WHERE user = :user AND id=:id");
    $sql->execute([
        ":user" => UserID,
        ":id" => $_POST['id'],
        ":start" => $_POST['start'],
        ":end" => $_POST['end']
    ]);
    $users = $sql->fetchAll();
}
catch (PDOException $e) {API::error($e);}

API::output($data);