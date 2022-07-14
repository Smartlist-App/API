<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'id', 'note']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare(
        "UPDATE Goals SET note = :note WHERE id = :id AND user = :user"
    );
    $sql->execute([
        ":user" => UserID,
        ":id" => $_POST['id'],
        ":note" => Encryption::encrypt($_POST['note'])
    ]);
    $data['data'] = $_POST['note'];
}
catch (PDOException $e) {API::error($e);}

API::output($data);