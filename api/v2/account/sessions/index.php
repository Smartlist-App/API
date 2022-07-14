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
    $sql = $dbh->prepare("SELECT * FROM UserTokens WHERE user = :id ORDER BY id DESC");
    $sql->execute([":id" => UserID]);
    $users = $sql->fetchAll();
    $data['data'] = [];
    foreach($users as $user) {
        $data['data'][] = [
            "id" => intval($user['id']),
            "token" => $user['token'],
            "user" => intval($user['user']),
        ];
    }
}
catch (PDOException $e) {API::error($e);}

API::output($data);