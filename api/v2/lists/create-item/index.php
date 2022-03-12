<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'parent', 'description', 'title']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("INSERT INTO ListItems (parent, user, title, description) VALUES (:parent, :user, :title, :description)");
    $sql->execute([
        ":parent" => intval($_POST['parent']),
        ":title" => Encryption::encrypt($_POST['title']),
        ":description" => Encryption::encrypt($_POST['description']),
        ":user" => UserID
    ]);
    $data['data'] = [
        'id' => $dbh->lastInsertId(),
        'title' => $_POST['title'],
        'description' => $_POST['description']
    ];
}
catch (PDOException $e) {API::error($e);}

API::output($data);
