<?php
ini_set("display_errors", 1);

$data = new stdClass();
$data->data = null;
$data->success = false;
$data->method = $_SERVER['REQUEST_METHOD'];
require '/home/smartlist/domains/smartlist.tech/private_html/app/cred.php';
require '/home/smartlist/domains/smartlist.tech/private_html/app/encrypt.php';
require '/home/smartlist/domains/smartlist.tech/private_html/api/v2/header.php';

$data->error = "Cannot ".$_SERVER['REQUEST_METHOD']." ".__FILE__;

if($_SERVER['REQUEST_METHOD'] !== "POST") die(json_encode($data));

APIVerification::requireParams(['token']);

$data->error = null;
$data->success = true;
$userID = APIVerification::fetchUserID($_POST['token']);

$themes = array();

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT * FROM Accounts WHERE id = :id");
    $sql->execute(array(
        ":id" => $userID
    ));
    $users = $sql->fetchAll();
    foreach($users as $user) {
        $data->data = new stdClass();
        switch($user['theme']) {
            case "c62828": 
                // Red
                $themes = array(
                    "dark" => [183, 28, 28],
                    "original" => [198, 40, 40],
                    "light" => [211, 47, 47],
                    "tint" => [255, 235, 238]
                );
                break;
            case "ad1457": 
                // Pink
                $themes = array(
                    "dark" => [136, 14, 79],
                    "original" => [173, 20, 87],
                    "light" => [194, 24, 91],
                    "tint" => [252, 228, 236]
                );
                break;
            case "6a1b9a": 
                // Purple
                $themes = array(
                    "dark" => [74, 20, 140],
                    "original" => [106, 27, 154],
                    "light" => [123, 31, 162],
                    "tint" => [243, 229, 245]
                );
                break;
            case "283593": 
                // Indigo
                $themes = array(
                    "dark" => [26, 35, 126],
                    "original" => [40, 53, 147],
                    "light" => [48, 63, 159],
                    "tint" => [232, 234, 246]
                );
                break;
            case "1565c0": 
                // Blue
                $themes = array(
                    "dark" => [13, 71, 161],
                    "original" => [21, 101, 192],
                    "light" => [25, 118, 210],
                    "tint" => [227, 242, 253]
                );
                break;
            case "00838f": 
                // Cyan
                $themes = array(
                    "dark" => [0, 96, 100],
                    "original" => [0, 131, 143],
                    "light" => [0, 151, 167],
                    "tint" => [224, 247, 250]
                );
                break;
            case "00695c": 
                // Teal
                $themes = array(
                    "dark" => [0, 77, 64],
                    "original" => [0, 105, 92],
                    "light" => [0, 121, 107],
                    "tint" => [224, 242, 241]
                );
                break;
            case "d84315": 
                // Orange
                $themes = array(
                    "dark" => [191, 54, 12],
                    "original" => [216, 67, 21],
                    "light" => [230, 74, 25],
                    "tint" => [251, 233, 231]
                );
                break;
            default: 
                $themes = array(
                    "dark" => [27, 94, 32],
                    "original" => [46, 125, 50],
                    "light" => [56, 142, 60],
                    "tint" => [232, 245, 233]
                );
                break;
        }
        $data->data->id = $user['id'];
        $data->data->email = Encryption::decrypt($user['email']);
        $data->data->name = Encryption::decrypt($user['name']);
        $data->data->financePlan = Encryption::decrypt($user['financePlan']);
        $data->data->image = $user['image'];
        $data->data->notificationMin = intval($user['notificationMin']);
        $data->data->budget = intval($user['budget']);
        $data->data->onboarding = intval($user['onboarding']);
        $data->data->verifiedEmail = $user['verifiedEmail'];
        $data->data->defaultPage = $user['defaultPage'];
        $data->data->studentMode = $user['studentMode'];
        $data->data->familyCount = $user['familyCount'];
        $data->data->houseName = Encryption::decrypt($user['houseName']);
        $data->data->currency = $user['currency'];
        $data->data->theme = $themes;
    }
}
catch (PDOException $e) {var_dump($e);}

echo json_encode($data);
?>