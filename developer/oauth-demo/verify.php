<?php

$url = "https://api.smartlist.ga/v1/oauth/credentials/";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
  "Authorization: Bearer censored_for_security",
  "Content-Type: application/x-www-form-urlencoded",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data = "token=".$_GET['token'];

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
$resp = json_decode($resp);
$name = $resp->name;
$email = $resp->email;
$avatar = $resp->user_avatar;
$id = $resp->id;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Demo</title>
  </head>
  <body style="font-family:arial">
    <center>
      <h5>Success!</h5>
      <img src="<?=$avatar;?>" style="width: 300px;border-radius:9999px;height:300px;object-fit:cover">
      <h1><?=$name;?></h1>
      <h2><?=$email;?></h2>
      <p>User ID: <?=$id;?></p>
    </center>
  </body>
</html>