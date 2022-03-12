<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'data']);
API::set('success', true);
define('UserID', API::fetchUserID($_POST['token']));

$allowedValues = ['name', 'email', 'image', 'houseName', 'familyCount', 'studentMode', 'defaultPage', 'purpose', 'theme'];
$values = json_decode($_POST['data']);

foreach(get_object_vars($values) as $key=>$value) {
    if(!in_array($key, $allowedValues)) {
        $data['error'] = "You aren't allowed to set the `".$key."` value";
        API::output($data);
    }
}

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    foreach(get_object_vars($values) as $key=>$value) {
        $sql = $dbh->prepare("UPDATE Accounts SET ".$key." = :value WHERE id = :id");
        
        if($key == "name" || $key == "email" || $key == "houseName" || $key == "image") {
            $value = Encryption::encrypt($value);
        }

        $sql->execute([ ":id" => UserID, ":value" => $value ]);
    }
}
catch (PDOException $e) {API::error($e);}

API::output($data);