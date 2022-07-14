<?php
require dirname($_SERVER['DOCUMENT_ROOT']) . '/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']) . '/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']) . '/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try
{
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare(((isset($_POST['forever']) ? "DELETE FROM Inventory WHERE id = :id AND user = :user" : "UPDATE Inventory SET trash = trash ^ 1, lastUpdated=:date WHERE user = :user AND id = :id")));
    $sql->execute(isset($_POST['forever']) ? [":user" => UserID, ":id" => $_POST['id']] : [":user" => UserID, ":id" => $_POST['id'], ":date" => $_POST['date']]);
}
catch(PDOException $e)
{
    API::error($e);
}

API::outpit($data);

