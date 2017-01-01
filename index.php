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
  <script src="https://code.highcharts.com/stock/highstock.js"></script>
  <script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
  <script src="js/delay.js"></script>
  <script src="js/diagramm.js"></script>
  <script src="js/index.js"></script>
  <script src="js/funcMaps.js"></script>
  <link rel="stylesheet" href="css/main.css">
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
          <li class="active"><a href="./index.php">Home</a></li>
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
      <div class="col-sm-5 col-sm-offset-0">
          <img id="liveImage" src="img/0.jpg?t=" class="img-responsive" />
      </div>
      <div class="col-sm-7">
          <div id="container" style="height: 400px; min-width: 310px"></div>
      </div>
      </div>
      <div class="row content">
      <div class="col-sm-12 col-sm-offset-0">
          <hr>
          <div id="map" style="width:100%;height:400px"></div>
      </div>
      </div>
  </div>
  <?php
  include "php/footer.php";
  ?>
      <script src="js/userMaps.js"></script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBKiyusanWE3AYe4pMObUTGYhsnb45O2pI&callback=myMap&libraries=drawing"></script>
  </body>

</html>