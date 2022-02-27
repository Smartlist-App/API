<?php
require '/home/smartlist/domains/smartlist.tech/private_html/app/cred.php';
require '/home/smartlist/domains/smartlist.tech/private_html/app/encrypt.php';
require '/home/smartlist/domains/smartlist.tech/private_html/api/v2/header.php';

$data = new stdClass();
$data->data = null;
$data->error = null;

API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'parent', 'description', 'title']);
define('UserID', API::fetchUserID($_POST['token']));

$data->success = true;
$data->data = [];

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("INSERT INTO ListItems (parent, user, title, description) VALUES (:parent, :user, :title, :description)");
    $sql->execute(array(
        ":parent" => intval($_POST['parent']),
        ":title" => Encryption::encrypt($_POST['title']),
        ":description" => Encryption::encrypt($_POST['description']),
        ":user" => UserID
    ));
    $data->data = "Created \"".htmlspecialchars($_POST['name'])."\" in ".htmlspecialchars($_POST['room']);
    if(isset($_POST['parent'])) {
        $data->data = "Created item!";
    }
}
catch (PDOException $e) {var_dump($e);}

echo json_encode($data);
?>