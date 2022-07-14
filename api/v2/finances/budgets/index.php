<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

function decrypt($input) {
    $input['category'] = Encryption::decrypt($input['category']);
    return $input;
}

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare(
        "SELECT * FROM FinanceBudgets WHERE login = :id"
    );
    $sql->execute([
        ":id" => UserID,
    ]);
    $res = $sql->fetchAll(PDO::FETCH_ASSOC);
    $data['data'] = array_map('decrypt', $res);
}
catch (PDOException $e) {API::error($e);}

API::output($data);