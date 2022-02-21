<?php
require '/home/smartlist/domains/smartlist.tech/private_html/app/cred.php';
require '/home/smartlist/domains/smartlist.tech/private_html/app/encrypt.php';
require '/home/smartlist/domains/smartlist.tech/private_html/api/v2/header.php';

$data = new stdClass();
$data->data = null;
$data->error = null;

API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'title', 'start', 'end', 'type']);
define('UserID', API::fetchUserID($_POST['token']));

$data->success = true;
$data->data = new stdClass();

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("INSERT INTO Meals (title, start, end, user, type) VALUES (:title, :start, :end, :user, :type)");
    $sql->execute(array(
        ":title" => $_POST['title'],
        ":start" => $_POST['start'],
        ":end" => $_POST['end'],
        ":user" => UserID,
        ":type" => $_POST['type'],
    ));
    $data->data->id = $dbh->lastInsertId();
}
catch (PDOException $e) {API::error($e);}

echo API::output($data);