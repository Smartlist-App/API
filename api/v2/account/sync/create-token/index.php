<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'houseName', 'email']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare(
    "INSERT INTO SyncTokens (token, email, login, houseName) VALUES (:token, :email, :login, :houseName)"
    );
    $sql->execute([
        ":token" => $_POST['token'],
        ":houseName" => $_POST['houseName'],
        ":email" => $_POST['email'],
        ":login" => UserID
    ]);
}
catch (PDOException $e) {API::error($e);}

API::output($data);