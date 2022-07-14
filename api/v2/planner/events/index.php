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
    $sql = $dbh->prepare("SELECT * FROM Planner WHERE user=:id");
    $sql->execute([
        ":id" => UserID
    ]);
    $users = $sql->fetchAll(PDO::FETCH_OBJ);
    $data['data'] = array_map(function($e) {
        return [
            "id" => intval($e->id),
            "startDate" => Encryption::decrypt($e->startDate),
            "endDate" => Encryption::decrypt($e->endDate),
            "type" => Encryption::decrypt($e->type),
            "title" => Encryption::decrypt($e->title),
            "field1" => Encryption::decrypt($e->field1),
        ];
    }, $users);
}
catch (PDOException $e) {API::error($e);}

API::output($data);
