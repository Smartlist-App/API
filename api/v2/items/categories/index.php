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
    $sql = $dbh->prepare("SELECT `category`, `id` FROM Inventory WHERE user = :id ORDER BY ID DESC");
    $sql->execute([ ":id" => UserID ]);
    $data['data'] = [];    
    $users = $sql->fetchAll();
    foreach($users as $row) {
        $obj = new stdClass();
        $obj->id = $row['id'];
        $obj->categories = Encryption::decrypt($row['category']);
        $data['data'][] = $obj;
    }
    $data['data'] = returnUniqueProperty($data['data'],'categories');
}
catch (PDOException $e) {API::error($e);}

API::error($data);