<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">

  <head>
    <?php
      include "php/head.php";
    ?>
  
    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
    <script src="js/delay.js"></script>
    <script src="js/diagramm.js"></script>
    <script src="js/index.js"></script>
    <script src="js/funcMaps.js"></script>
    <link rel="stylesheet" href="css/main.css">
  </head>

  <body>
    <?php
      include "php/nav.php";
    ?>
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