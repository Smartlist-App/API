<?php
require '/home/smartlist/domains/smartlist.tech/private_html/app/cred.php';
require '/home/smartlist/domains/smartlist.tech/private_html/app/encrypt.php';
require '/home/smartlist/domains/smartlist.tech/private_html/api/v2/header.php';

$data = new stdClass();
$data->data = null;
$data->error = null;

API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'description', 'title', 'star']);
define('UserID', API::fetchUserID($_POST['token']));

$data->success = true;

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("INSERT INTO ListNames (user, title, description, star) VALUES (:user, :title, :description, :star)");
    $sql->execute([
        ":user" => UserID,
        ":title" => $_POST['title'],
        ":description" => $_POST['description'],
        ":star" => intval($_POST['star'])
    ]);
    $data->data = new stdClass();
    $data->data->id = $dbh->lastInsertId();
    $data->data->title = $_POST['title'];
    $data->data->description = $_POST['description'];
}
catch (PDOException $e) {API::error($e);}

API::output($data);
