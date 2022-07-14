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
        $data['data'] = [
            "id" => $user['id'],
            "email" => Encryption::decrypt($user['email']),
            "email" => Encryption::decrypt($user['email']),
            "name" => Encryption::decrypt($user['name']),
            "financePlan" => $user['financePlan'],
            "image" => Encryption::decrypt($user['image']),
            "notificationMin" => intval($user['notificationMin']),
            "SyncToken" => $user['SyncToken'] !== null ? Encryption::decrypt($user['SyncToken']) : false,
            "onboarding" => intval($user['onboarding']),
            "verifiedEmail" => $user['verifiedEmail'],
            "purpose" => $user['purpose'],
            "defaultPage" => $user['defaultPage'],
            "studentMode" => $user['studentMode'],
            "financeToken" => $user['FinanceToken'],
            "familyCount" => $user['familyCount'],
            "houseName" => Encryption::decrypt($user['houseName']),
            "currency" => $user['currency'],
            "budgetDaily" => intval($user['budgetDaily']),
            "budgetWeekly" => intval($user['budgetWeekly']),
            "budgetMonthly" => intval($user['budgetMonthly']),
            "darkMode" => $user['darkMode'],
            "theme" => $user['theme']
        ];
    }
}
catch (PDOException $e) {API::error($e);}

API::output($data);