<?php
ini_set("display_errors", 1);
header("Content-Type: application/json");

class ApiVerification extends App
{
   public static function verify($data)
   {
      try {
         $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::apiDatabase, App::apiUser, App::apiPassword);
         $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
         $sql = $dbh->prepare("SELECT * FROM ApiKeys WHERE token = :token");
         $sql->execute(array(
            ":token" => $data
         ));
         $keys = $sql->fetchAll();
         return count($keys) == 1;
      } catch (PDOException $e) {
         var_dump($e);
      }
   }
   public static function fetchUserID($token)
   {
      try {
         $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
         $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
         $sql = $dbh->prepare("SELECT * FROM UserTokens WHERE token = :token");
         $sql->execute(array(
            ":token" => $token
         ));
         $tokens = $sql->fetchAll();
         $data = -1;
         foreach ($tokens as $token) {
            $data = $token['user'];
         }
         return $data;
      } catch (PDOException $e) {
         var_dump($e);
      }
   }
   public static function requireParams($arr)
   {
      foreach ($arr as $d) {
         if (!isset($_POST[$d])) {
            $GLOBALS['data']->error = "Missing parameter: `" . $d . "`";
            die(json_encode($GLOBALS['data']));
         }
      }
   }
}

class_alias('ApiVerification', 'API');

function returnUniqueProperty($array, $property)
{
   $tempArray = array_unique(array_column($array, $property));
   $moreUniqueArray = array_values(array_intersect_key($array, $tempArray));
   return $moreUniqueArray;
}
