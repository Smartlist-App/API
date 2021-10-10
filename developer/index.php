<?php
session_start();
include("../dashboard/cred.php");
if(!isset($_SESSION['valid'])) {
  header("Location: https://smartlist.ga/dashboard/auth");
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@materializecss/materialize@1.1.0-alpha/dist/css/materialize.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <meta name="title" content="Smartlist - the free home inventory database">
    <meta name="theme-color" content="#29353b">
    <meta name="description" content="Smartlist is a free home inventory app that lets you keep track of what you have in your home, and helps you save money">
    <meta property="og:site_name" content="smartlist.ga" />
    <meta property="og:title" content="Smartlist" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="https://cdn.jsdelivr.net/gh/Smartlist-app/Assets/cover.png" />
    <link rel="shortcut icon" href="https://smartlist.ga/dashboard/icon/roofing.svg">
    <link rel="favicon" href="https://smartlist.ga/dashboard/icon/roofing.svg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/malihu-custom-scrollbar-plugin@3.1.5/jquery.mCustomScrollbar.css">
    <meta name="robots" content="index, nofollow" />
    <link rel="apple-touch-icon" href="https://smartlist.ga/dashboard/icon/roofing.svg" />
    <meta property="og:url" content="https://smartlist.ga/dashboard/beta" />
    <meta property="og:description" content="Smartlist is a free home inventory app that lets you keep track of what you have in your home, and helps you save money" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@Smartlist">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js" type="48cadd2c2a7399f1527eb501-text/javascript"></script>
    <meta name="twitter:title" content="Smartlist">
    <meta name="twitter:description" content="Smartlist is a free home inventory app that lets you keep track of what you have in your home, and helps you save money">
    <meta name="twitter:domain" content="smartlist.ga">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
      code,pre { white-space: pre!important; }
      .pln { color: #4d4d4c !important; } ol.linenums { margin-top: 0; margin-bottom: 0; color: #8e908c } li.L0, li.L1, li.L2, li.L3, li.L4, li.L5, li.L6, li.L7, li.L8, li.L9 { padding-left: 1em; background-color: #fff; list-style-type: decimal } @media screen { .str { color: #718c00 !important; } .kwd { color: #8959a8 !important; } .com { color: #8e908c !important; } .typ { color: #4271ae !important; } .lit { color: #f5871f !important; } .pun { color: #4d4d4c !important; } .opn { color: #4d4d4c !important; } .clo { color: #4d4d4c !important; } .tag { color: #c82829 !important; } .atn { color: #f5871f !important; } .atv { color: #3e999f !important; } .dec { color: #f5871f !important; } .var { color: #c82829 !important; } .fun { color: #4271ae !important; } }
      .sidenav .background img {width: 100%}
      #app{transition:all .2s;transform-origin: 50vw 50vh;}
      * {scroll-behavior:smooth}
      body {
        padding-left: 100px;
        padding-right: 50px;
        padding-top: 30px
      }
      ::-webkit-scrollbar {display:none}
      .rail {
        position: fixed;
        top: 0;
        border-right: 1px solid #eee;
        left: 0;
        z-index: 10;
        background: white;
        text-align: center;
        padding: 10px;
        height: 100vh;
        padding-bottom: 65px;
        overflow-y:auto
      }
      .rail a {
        color: gray !important;
        text-align: center;
        display: block;
        height: auto;
        box-shadow: none !important;
        background: transparent !important
      }
      .rail a.active {
        background: #b0bec5 !important;
      }
      .rail a i:not(.rail a.active i) {
        color: gray !important;
      }
      .rail a.active i {
        color: #37474f
      }
      .waves-effect:not(.waves-light, ._darkTheme .waves-effect) .waves-ripple {
        background: rgba(0, 0, 0, .4) !important
      }
      .waves-ripple {
        transition: transform .8s cubic-bezier(0.4, 0, 0.2, 1), opacity .4s !important
      }
      .material-tooltip {
        background: rgba(35, 47, 52, 0.7);
        border-radius: 4px;
        padding: 0 10px !important;
        line-height: 35px !important;
        height: 10px !important;
        overflow: hidden;
      }
      nav a {margin: 0 !important}
    </style>
  </head>
  <body>
    <div class="rail">
      <a onclick="toggleS(this); loadPage('./pages/dashboard.php')" href="#/api-keys" class="waves-effect active tooltipped btn-floating btn-flat btn-large" data-tooltip="Home" data-position="right">
        <i class="material-icons">home</i>
      </a>
      <br>
      <div class="divider"></div>
      <br>
      <a onclick="toggleS(this); loadPage('./pages/keys.php')" href="#/api-keys" class="waves-effect tooltipped btn-floating btn-flat btn-large" data-tooltip="API keys" data-position="right">
        <i class="material-icons">key</i>
      </a>

      <a onclick="toggleS(this); loadPage('./pages/apiReference.php')" href="#/api-keys" class="waves-effect tooltipped btn-floating btn-flat btn-large" data-tooltip="API Reference" data-position="right">
        <i class="material-icons">library_books</i>
      </a>

      <a onclick="toggleS(this); loadPage('./pages/examples.php')" href="#/api-keys" class="waves-effect tooltipped btn-floating btn-flat btn-large" data-tooltip="API usage and Examples" data-position="right">
        <i class="material-icons">auto_awesome</i>
      </a>
      <br>
      <div class="divider"></div>
      <br>
      <a onclick="toggleS(this); loadPage('./pages/apps.php')" href="#/api-keys" class="waves-effect tooltipped btn-floating btn-flat btn-large" data-tooltip="My Apps" data-position="right">
        <i class="material-icons">apps</i>
      </a>

      <a onclick="toggleS(this); loadPage('./pages/loginAPI.php')" href="#/api-keys" class="waves-effect tooltipped btn-floating btn-flat btn-large" data-tooltip="Login API" data-position="right">
        <i class="material-icons">password</i>
      </a>
      <br>
      <div class="divider"></div>
      <br>

      <a onclick="setTimeout(() => window.open('https://support.smartlist.ga'), 500)" href="#/api-keys" class="waves-effect tooltipped btn-floating btn-flat btn-large" data-tooltip="Support" data-position="right">
        <i class="material-icons">help</i>
      </a>

      <a onclick="toggleS(this); loadPage('./pages/feedback.php')" href="#/feedback" class="waves-effect tooltipped btn-floating btn-flat btn-large" data-tooltip="Feedback / Help" data-position="right">
        <i class="material-icons">feedback</i>
      </a>

      <a  href="#/api-keys" draggable="false" class="waves-effect tooltipped btn-floating btn-flat btn-large" style="position: fixed;bottom: 10px" data-tooltip="<?=$_SESSION['name'];?>" data-position="right">
        <img src="<?=$_SESSION['avatar']?>" style="width: 100%" draggable="false">
      </a>
    </div>
    <div id="app"></div>
    <script src="https://cdn.jsdelivr.net/npm/@materializecss/materialize@1.1.0-alpha/dist/js/materialize.min.js"></script>
    <script>
      function toggleS(el) {
        $("a").removeClass("active");
        $(el).addClass("active");
      }
      function loadPage(file) {
        document.getElementById('app').style.transform = "scale(.9)"
        document.getElementById('app').style.opacity = "0"
        $("#app").load(file, () => {
          document.getElementById('app').style.transform = "scale(1)";
        document.getElementById('app').style.opacity = "1"
          
        });
        window.scrollTo(0,0)
      }
      loadPage("./pages/dashboard.php")
      $(".sidenav").sidenav()
      $(".tooltipped").tooltip()
    </script>
  </body>
</html>