<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'parent']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT * FROM ListItems WHERE parent = :id AND user=:user ORDER BY ID ASC");
    $sql->execute(array(
        ":user" => UserID,
        ":id" => $_POST['parent']
    ));
    $data['data'] = [];    
    $users = $sql->fetchAll();
    foreach($users as $row) {
        $data['data'][] = [
            'id' => $row['id'],
            'title' => Encryption::decrypt($row['title']),
            'description' => Encryption::decrypt($row['description'])
        ];
    }
}
catch (PDOException $e) {API::error($e);}

API::output($data);