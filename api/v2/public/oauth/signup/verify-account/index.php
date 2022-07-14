<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['code', 'id']);

function VerifyToken($id, $token) {
  try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT `id` FROM Accounts WHERE id = :id AND verifyToken = :token");
    $sql->execute([":id" => $id, ":token" => $token]);
    $users = $sql->fetchAll();
    return count($users)  === 1;
  } catch (PDOException $e) {
    var_dump($e);
  }
}
function VerifyAccount($id) {
  try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("Update Accounts SET verifiedEmail = 1 WHERE id = :id");
    $sql->execute([":id" => $id]);
    $users = $sql->fetchAll();
  } catch (PDOException $e) {
    var_dump($e);
  }
}

if(!VerifyToken($_POST['id'], $_POST['code'])) API::error("Incorrect code, please try again");
VerifyAccount($_POST['id']);

$data['data'] = null;
API::set('success', true);
API::output($data);