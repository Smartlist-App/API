<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'title', 'content']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("UPDATE Notes SET title=:title, content=:content WHERE id=:id AND user=:user");
    $sql->execute([
        ":title" => Encryption::encrypt($_POST['title']),
        ":content" => Encryption::encrypt($_POST['content']),
        ":user" => UserID,
        ":id" => $_POST['id']
    ]);
    $users = $sql->fetchAll();
    $data['data'] = "All changes are saved";
}
catch (PDOException $e) {API::error($e);}

API::output($data);