<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">

  <head>
    <title>WETboat</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="img/favicon-128x128.png" sizes="128x128">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css">
  </head>

  <body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand"><img alt="Brand" src="img/Logo_website_new.png"></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav">
            <li><a href="./index.php">Home</a></li>
            <li><a href="#">Über Uns</a></li>
            <li><a href="#">Projekt</a></li>
            <li><a href="#">Kontakt</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <?php
              if(isset($_SESSION['username'])){
                  echo "<li><a id='txtSession'>Herzlich Willkommen, ".$_SESSION['username']."!</a></li>";
                  echo "<li><a href='./admin.php' id='btnLogin'>Admin</a></li>";
                  echo "<li><a href='php/logout.php' id='btnLogin'><span class='glyphicon glyphicon-log-out'></span> Abmelden</a></li>";
              } else {
                  echo "<li><a href='./login.php' id='btnLogin'><span class='glyphicon glyphicon-log-in'></span> Anmelden</a></li>";
              }
            ?>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container-fluid">
      <div class="row content">
        <div class="col-sm-4 col-sm-offset-5 text-left">
          <h2>Impressum</h2>
          <p>Mario Kiefer</p>
          <br>
          <p>Jadorf 7</p>
          <p>5431 Kuchl</p>
          <p>Österreich</p>
        </div>
      </div>
    </div>
    <?php
      include "php/footer.php";
    ?>
  </body>

</html>