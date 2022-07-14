<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'type', 'category', 'amount']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare(
        "INSERT INTO FinanceBudgets (login, amount, category, type) VALUES (:login, :amount, :category, :type)"
    );
    $sql->execute([
        ":type" => $_POST['type'],
        ":category" => Encryption::encrypt($_POST['category']),
        ":amount" => $_POST['amount'],
        ":login" => UserID
    ]);

    $data['data'] = [
        "id" => $dbh->lastInsertId(),
        "type" => $_POST['type'],
        "category" => $_POST['category'],
        "amount" => $_POST['amount']
    ];
}
catch (PDOException $e) {API::error($e);}

API::output($data);