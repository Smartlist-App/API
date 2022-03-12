<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token']);
API::set('success', true);

define('UserID', API::fetchUserID($_POST['token']));

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT * FROM Accounts WHERE id = :id");
    $sql->execute([":id" => UserID]);
    $users = $sql->fetchAll();
    foreach($users as $user) {
        switch($user['theme']) {
            case "c62828": 
                // Red
                $themes = [
                    "dark" => [183, 28, 28],
                    "original" => [198, 40, 40],
                    "light" => [211, 47, 47],
                    "tint" => [255, 235, 238]
                ];
                break;
            case "ad1457": 
                // Pink
                $themes = [
                    "dark" => [136, 14, 79],
                    "original" => [173, 20, 87],
                    "light" => [194, 24, 91],
                    "tint" => [252, 228, 236]
                ];
                break;
            case "6a1b9a": 
                // Purple
                $themes = [
                    "dark" => [74, 20, 140],
                    "original" => [106, 27, 154],
                    "light" => [123, 31, 162],
                    "tint" => [243, 229, 245]
                ];
                break;
            case "283593": 
                // Indigo
                $themes = [
                    "dark" => [26, 35, 126],
                    "original" => [40, 53, 147],
                    "light" => [48, 63, 159],
                    "tint" => [232, 234, 246]
                ];
                break;
            case "1565c0": 
                // Blue
                $themes = [
                    "dark" => [13, 71, 161],
                    "original" => [21, 101, 192],
                    "light" => [25, 118, 210],
                    "tint" => [227, 242, 253]
                ];
                break;
            case "00838f": 
                // Cyan
                $themes = [
                    "dark" => [0, 96, 100],
                    "original" => [0, 131, 143],
                    "light" => [0, 151, 167],
                    "tint" => [224, 247, 250]
                ];
                break;
            case "00695c": 
                // Teal
                $themes = [
                    "dark" => [0, 77, 64],
                    "original" => [0, 105, 92],
                    "light" => [0, 121, 107],
                    "tint" => [224, 242, 241]
                ];
                break;
            case "d84315": 
                // Orange
                $themes = [
                    "dark" => [191, 54, 12],
                    "original" => [216, 67, 21],
                    "light" => [230, 74, 25],
                    "tint" => [251, 233, 231]
                ];
                break;
            default: 
                $themes = [
                    "dark" => [27, 94, 32],
                    "original" => [46, 125, 50],
                    "light" => [56, 142, 60],
                    "tint" => [232, 245, 233]
                ];
                break;
        }
        $data['data'] = [
            "id" => $user['id'],
            "email" => Encryption::decrypt($user['email']),
            "email" => Encryption::decrypt($user['email']),
            "name" => Encryption::decrypt($user['name']),
            "financePlan" => Encryption::decrypt($user['financePlan']),
            "image" => Encryption::decrypt($user['image']),
            "notificationMin" => intval($user['notificationMin']),
            "budget" => intval($user['budget']),
            "onboarding" => intval($user['onboarding']),
            "verifiedEmail" => $user['verifiedEmail'],
            "purpose" => $user['purpose'],
            "defaultPage" => $user['defaultPage'],
            "studentMode" => $user['studentMode'],
            "financeToken" => $user['FinanceToken'],
            "familyCount" => $user['familyCount'],
            "houseName" => Encryption::decrypt($user['houseName']),
            "currency" => $user['currency'],
            "theme" => $themes
        ];
    }
}
catch (PDOException $e) {API::error($e);}

API::output($data);