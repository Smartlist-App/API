<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'name', 'image', 'minAmountOfMoney', 'accountId']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare(
        "INSERT INTO Goals (name, image, note, completed, minAmountOfMoney, user, accountId) VALUES (:name, :image, :note, 'false', :minAmountOfMoney, :user, :accountId)"
    );
    $sql->execute([
        ":name" => Encryption::encrypt($_POST['name']),
        ":image" => $_POST['image'],
        ":note" => Encryption::encrypt(""),
        ":minAmountOfMoney" => Encryption::encrypt($_POST['minAmountOfMoney']),
        ":user" => UserID,
        ":accountId" => $_POST['accountId']
    ]);

    $data['data'] = [
        "id" => $dbh->lastInsertId(),
        "name" => $_POST['name'],
        "image" => $_POST['image'],
        "note" => "",
        "completed" => false,
        "minAmountOfMoney" => $_POST['minAmountOfMoney'],
        "accountId" => $_POST['accountId']
    ];
}
catch (PDOException $e) {API::error($e);}

API::output($data);