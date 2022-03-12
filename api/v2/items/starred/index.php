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
    $sql = $dbh->prepare("SELECT * FROM Inventory WHERE user = :id AND star = 1 AND trash = 0");
    $sql->execute([ ":id" => UserID ]);
    $data['data'] = [];    
    $users = $sql->fetchAll();
    foreach($users as $row) {
        $data['data'][] = [
            "id" => $row['id'],
            "lastUpdated" => $row['lastUpdated'],
            "amount" => Encryption::decrypt($row['qty']),
            "sync" => $row['user'] == UserID ? 0 : 1,
            "title" => Encryption::decrypt($row['name']),
            "categories" => Encryption::decrypt($row['category']),
            "note" => (isset($row['note']) && !empty($row['note']) ? Encryption::decrypt($row['note']):""),
            "star" => $row['star'],
            "room" => $row['room']
        ];
    }
}
catch (PDOException $e) {API::error($e);}

API::output($data);