<?php
// require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
// require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
// require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

require "./email/index.php";

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['name', 'email', 'password']);

function VerifyAccountAvailability($email) {
  try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT `email` FROM Accounts");
    $sql->execute();
    $users = $sql->fetchAll();
    $available = true;
    foreach ($users as $row) {
      $e = new Encryption();
      if ($e->decrypt($row['email']) == $email) {
        $available = false;
      }
    }
    return $available;
  } catch (PDOException $e) {
    var_dump($e);
  }
}

function CreateAccount($name, $email, $password) {
  try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=".App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("

    INSERT INTO Accounts (
      name,
      email,
      password,
      image,
      onboarding,
      theme,
      notificationMin,
      verifiedEmail,
      defaultPage,
      studentMode,
      financePlan,
      purpose,
      houseName,
      familyCount,
      currency,
      verifyToken
    ) 

   VALUES (
      :name,  
      :email,
      :password,
      :image,
      1,
      'purple',
      5,
      0,
      'dashboard',
      'true',
      :financePlan,
      'personal',
      :houseName,
      3,
      'dollar',
      :token
   )
   ");
    $code = rand(100000, 999999);
    $sql->execute([
      ":name" => Encryption::encrypt($name),
      ":email" => Encryption::encrypt($email),
      ":image" => Encryption::encrypt(''),
      ":password" => password_hash($password, PASSWORD_ARGON2I),
      ":financePlan" => Encryption::encrypt("medium-term"),
      ":houseName" => Encryption::encrypt("Smartlist"),
      ":token" => $code
    ]);
    SendVerificationEmail($email, $code);
    $users = $sql->fetchAll();
    return $dbh->lastInsertId();
  }
  catch (PDOException $e) {API::error($e);}
}

API::set('success', false);
if(!VerifyAccountAvailability( $_POST['email'] )) API::error("An account with this email already exists.");
$id = CreateAccount($_POST['name'], $_POST['email'], $_POST['password']);

$data['data'] = [
  'id' => $id,
];

API::set('success', true);
API::output($data);