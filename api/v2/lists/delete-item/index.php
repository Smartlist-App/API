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
    $sql = $dbh->prepare("DELETE FROM ListItems WHERE user = :user AND id = :id");
    $sql->execute(array(
        ":id" => $_POST['id'],
        ":user" => UserID
    ));
}
catch (PDOException $e) {API::error($e);}

API::output($data);
