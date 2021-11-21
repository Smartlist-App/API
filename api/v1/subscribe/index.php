<?php
session_start();
include("/home/smartlis/public_html/dashboard/cred.php");
$dbname = 'smartlis_events';
if(!isset($_GET['d'])) {
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare("INSERT INTO people (code, name, email)
    VALUES (:code, :name, :email)");
    // use execute() because no results are returned
    $sql->execute( array(":code" => $_GET['code'], ":name" => $_GET['name'], ":email" => $_GET['email']) );
    // echo "New record created successfully";
} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Document</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@materializecss/materialize@1.1.0-alpha/dist/css/materialize.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
      .waves-effect:not(.waves-light, ._darkTheme .waves-effect) .waves-ripple {
        background: rgba(0, 0, 0, .2) !important
      }
      ._darkTheme .waves-ripple {background: rgba(255, 255, 255, .2) !important}
      .waves-light .waves-ripple {background: rgba(255, 255, 255, .2) !important}
      nav a,nav a i {
        background: transparent !important;
        line-height: 65px !important
      }
      nav .waves-ripple {
        transition: all .5s !important
      }
      .card {overflow-x:auto;max-height: 100vh;}
      .waves-ripple {
        transition: transform .8s cubic-bezier(0.4, 0, 0.2, 1), opacity .4s !important
      }
    </style>
  </head>
  <body class="grey lighten-3 center">
    <center>
      <div class="card" style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);width: 500px;max-width: 90vw;">
        <div style="card-image">
          <img src="https://cdn.dribbble.com/users/791530/screenshots/6827794/clip-illustration-style-icons8_4x.png"style="width:100%"></div>
        <div class="card-content">
          <h5><b>Success!</b></h5>
          <p>You've been added to our waiting list, and will be notified before this event starts!</p>
          <br><br>
          <p><a href="https://collaborate.smartlist.ga">
<img src="https://i.ibb.co/S0FPvqm/user.png" style="width: 100px;vertical-align:middle"><b>Smartlist Events</b></a> - An app by <a href="//smartlist.ga">Smartlist</a></p>
        </div>
      </div>
    </center>
    <script src="https://cdn.jsdelivr.net/npm/@materializecss/materialize@1.1.0-alpha/dist/js/materialize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/ManuTheCoder/JS-Essentials/essentials.min.js"></script>
    <script src="script.js"></script>
  </body>
</html>

<script>
history.pushState('https://api.smartlist.ga/v1/subscribe/?d=false');
</script>