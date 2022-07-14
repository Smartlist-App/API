<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['appId']);
API::set('success', true);

try {
  $dbh = new PDO("mysql:host=" . App::server . ";dbname=smartlist_api", App::user, App::password);
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $sql = $dbh->prepare("SELECT * FROM Apps WHERE MD5(id) = :appId LIMIT 1");
  $sql->execute([':appId' => $_POST['appId']]);
  $users = $sql->fetchAll();
  if(count($users) === 1) {
    $data['data'] = [
      'name' => $users[0]['name'],
      'logo' => $users[0]['logo'],
      'redirect_uri' => $users[0]['redirect_uri'],
    ];
  }
}
catch (PDOException $e) {API::error($e);}

API::output($data);