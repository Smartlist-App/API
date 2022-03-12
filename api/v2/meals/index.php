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
    $sql = $dbh->prepare("SELECT * FROM Meals WHERE user = :user ORDER BY ID ASC");
    $sql->execute([ ":user" => UserID ]);
    $users = $sql->fetchAll();
    $data['data'] = [];
    foreach($users as $row) {
        $data['data'][] = [
            'id' => intval($row['id']),
            'title' => $row['title'],
            'start' => $row['start'],
            'end' => $row['end'],
            'type' => $row['type'],
            'foodId' => $row['id']
        ];
    }
}
catch (PDOException $e) {API::error($e);}

API::output($data);