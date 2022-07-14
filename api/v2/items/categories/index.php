<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

if(isset($_POST['category'])) {
    try {
        $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = $dbh->prepare("SELECT * FROM Inventory WHERE user = :id ORDER BY ID DESC");
        $sql->execute([ ":id" => UserID ]);
        $categories = [];    
        $users = $sql->fetchAll();
        foreach($users as $row) {
            if(in_array($_POST['category'], json_decode(Encryption::decrypt($row['category'])))) {
                $categories[] = [
                    "id" => $row['id'],
                    "lastUpdated" => $row['lastUpdated'],
                    "amount" => Encryption::decrypt($row['qty']),
                    "sync" => $row['user'] == UserID ? 0 : 1,
                    "title" => Encryption::decrypt($row['name']),
                    "categories" => json_decode(Encryption::decrypt($row['category'])),
                    "note" => (isset($row['note']) && !empty($row['note']) ? Encryption::decrypt($row['note']) : ""),
                    "star" => $row['star'],
                    "room" => $row['room']
                ];
            }
        }
        $data['data'] = $categories;
    }
    catch (PDOException $e) {API::error($e);}
}
else {
    try {
        $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = $dbh->prepare("SELECT `category`, `id` FROM Inventory WHERE user = :id ORDER BY ID DESC");
        $sql->execute([ ":id" => UserID ]);
        $categories = [];    
        $users = $sql->fetchAll();
        foreach($users as $row) {
            $categories = [...$categories, ...json_decode(Encryption::decrypt($row['category']))];
        }
        $data['data'] = array_values(array_unique(array_map('strval', $categories)));
    }
    catch (PDOException $e) {API::error($e);}
}

API::output($data);