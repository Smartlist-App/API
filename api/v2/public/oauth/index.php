<?php
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

API::init();
API::allowRequestMethods(["POST"]);
API::requireParams(['token', 'appId', 'secret']);
API::set('success', true);

try {
    $dbh = new PDO("mysql:host=" . App::server . ";dbname=smartlist_api", App::user, App::password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = $dbh->prepare("SELECT * FROM UserTokens WHERE token = :token LIMIT 1");
    $sql->execute([":token" => $_POST['token']]);
    $users = $sql->fetchAll();
    if(count($users) === 1) {
        
        $sql = $dbh->prepare("SELECT `id`, `secret` from Apps WHERE MD5(id) = :id AND secret = :secret LIMIT 1");
        $sql->execute([":id" => $_POST['appId'], ":secret" => $_POST['secret']]);
        $apps = $sql->fetchAll();
        if(count($apps) !== 1) {
            $data['error'] = "Invalid AppId / AppSecret";
            API::output($data);
        }
        if($users[0]['appid'] !== $_POST['appId']) {
            $data['error'] = "AppId does not match user token";
            API::output($data);
        }
        $data['success'] = true;
        $data['data'] = json_decode(Encryption::decrypt($users[0]['data']));
        unset($data['data']->income);
        unset($data['data']->financePlan);
        unset($data['data']->notificationMin);
        unset($data['data']->houseName);
        unset($data['data']->familyCount);
        unset($data['data']->defaultPage);
        unset($data['data']->budget);
        unset($data['data']->onboarding);

        // Provide a more friendly ID
        $data['data']->id = hash('whirlpool', md5($data['data']->id ));

        $sql = $dbh->prepare("DELETE FROM UserTokens WHERE token = :token");
        $sql->execute([":token" => $_POST['token']]);
    }
}
catch (PDOException $e) {API::error($e);}

API::output($data);