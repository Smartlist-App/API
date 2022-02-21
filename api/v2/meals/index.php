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
    $sql = $dbh->prepare("SELECT * FROM Meals WHERE user = :user ORDER BY ID ASC");
    $sql->execute([
        ":user" => UserID
    ]);
    $users = $sql->fetchAll();
    foreach($users as $row) {
        $obj = new stdClass();
        $obj->id = intval($row['id']);
        $obj->title = $row['title'];
        $obj->start = $row['start'];
        $obj->end = $row['end'];
        $obj->type = $row['type'];
        $obj->foodId = $row['id'];
        $data->data[] = $obj;
    }
}
catch (PDOException $e) {var_dump($e);}

echo json_encode($data);
?>