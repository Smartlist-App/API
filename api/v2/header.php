<?php
ini_set("display_errors", 1);
header("Content-Type: application/json");

class APIVerification extends App {
  public function verify($data) {
    try {
        $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::apiDatabase, App::apiUser, App::apiPassword);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = $dbh->prepare("SELECT * FROM ApiKeys WHERE token = :token");
        $sql->execute(array(
            ":token" => $data
        ));
        $keys = $sql->fetchAll();
        return count($keys) == 1;
    }
    catch (PDOException $e) {var_dump($e);}
  }
  public function fetchUserID($token) {
      try {
        $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = $dbh->prepare("SELECT * FROM UserTokens WHERE token = :token");
        $sql->execute(array(
            ":token" => $token
        ));
        $tokens = $sql->fetchAll();
        $data = -1;
        foreach($tokens as $token) {
            $data = $token['user'];
        }
        return $data;
    }
    catch (PDOException $e) {var_dump($e);}
  }
}

$res = new stdClass();
$res->method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "GET";
$res->error = "Cannot ".$res->method." ".__FILE__;
$res->data = null;