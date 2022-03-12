<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'id']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT * FROM Notes WHERE user = :user and id=:id ORDER BY ID ASC");
    $sql->execute([
        ":user" => UserID,
        ":id" => $_POST['id']
    ]);
    $users = $sql->fetchAll();
    foreach($users as $row) {
        $obj = new stdClass();
        $obj->id = intval($row['id']);
        $obj->title = Encryption::decrypt($row['title']);
        $obj->banner = $row['banner'] == "" ? "" : Encryption::decrypt($row['banner']);
        $obj->content = json_decode(Encryption::decrypt($row['content']));
        $data['data'] = $obj;
    }
}
catch (PDOException $e) {API::error($e);}

API::output($data);