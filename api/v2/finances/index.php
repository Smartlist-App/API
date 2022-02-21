<?php
require '/home/smartlist/domains/smartlist.tech/private_html/app/cred.php';
require '/home/smartlist/domains/smartlist.tech/private_html/app/encrypt.php';
require '/home/smartlist/domains/smartlist.tech/private_html/api/v2/header.php';

$data = new stdClass();
$data->data = null;
$data->error = null;

API::allowRequestMethods(["POST"]);
API::requireParams(['token']);
define('UserID', API::fetchUserID($_POST['token']));

$data->success = true;
$data->data = [];

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT `amount`, `date`, `categories`, `id` FROM Finances WHERE user = :id ORDER BY ID DESC");
    $sql->execute([
        ":id" => UserID
    ]);
    $users = $sql->fetchAll();
    foreach($users as $row) {
        $data->data[] = [
           "id" => intval($row['id']),
           "amount" => intval(Encryption::decrypt($row['amount'])),
           "date" => Encryption::decrypt($row['date']),
           "spentOn" => Encryption::decrypt($row['categories'])
        ];
    }
}
catch (PDOException $e) {
    API::error($e);
}

API::output($data);