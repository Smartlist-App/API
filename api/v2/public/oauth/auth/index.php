<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['appId']);

function VerifyApp($id) {
  try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=smartlist_api", App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT * FROM Apps WHERE MD5(id) = :appId LIMIT 1");
    $sql->execute([':appId' => $id]);
    $users = $sql->fetchAll();
    if(count($users) === 1) {
      return $users[0]['redirect_uri'];
    }
    else {
      return false;
    }
  }
  catch (PDOException $e) {API::error($e);}
}

function VerifyAccount($email, $password) {
  try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=".App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT * FROM Accounts");
    $sql->execute();
    $users = $sql->fetchAll();
    $valid = false;
    foreach($users as $user) {
      if(Encryption::decrypt($user['email']) === $email) {
        if(password_verify($password, $user['password'])) {
          $valid = true;
          return $user['id'];
          break;
        }
      }
    }
    if(!$valid) {
      return false;
    }
  }
  catch (PDOException $e) {API::error($e);}
}

function CreateAuthToken($id, $accessToken)
{
  try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=smartlist_data", App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("INSERT INTO UserTokens (user, token) VALUES (:user, :token)");
    $sql->execute([
      ":user" => $id,
      ":token" => $accessToken
    ]);
  } catch (PDOException $e) {
    var_dump($e);
  }
}

API::set('success', false);
// Verify if app is valid
$redirect_uri = VerifyApp($_POST['appId']);
if($redirect_uri === false) $data['error'] = API::error("Invalid app");

// Fetch user id if credentials are correct
$id = VerifyAccount($_POST['email'], $_POST['password']);
if($id === false) API::error("Invalid email or password");

// Valid session, let's create an access token
$token = bin2hex(random_bytes(300));
CreateAuthToken($id, $token);
$data['data'] = [
  'token' => $token,
  'redirect_uri' => $redirect_uri
];

API::set('success', true);
API::output($data);