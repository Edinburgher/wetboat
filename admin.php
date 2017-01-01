<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:login.php");
}

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
    <script src="js/delay.js"></script>
    <script src="js/funcMaps.js"></script>
    <script src="js/admin.js"></script>
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
            <li><a href="./index.php">Home</a></li>
            <li><a href="#">Über Uns</a></li>
            <li><a href="#">Projekt</a></li>
            <li><a href="#">Kontakt</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">

            <?php
            if(isset($_SESSION['username'])){
                echo "<li><a id='txtSession'>Herzlich Willkommen, ".$_SESSION['username']."!</a></li>";
                echo "<li class='active'><a href='./admin.php' id='btnLogin'>Admin</a></li>";
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
        <div class="col-sm-12 col-sm-offset-0">
          <a type="button" class="btn btn-info" id="btnNew">Neue Route</a>
          <a type="button" class="btn btn-info" id="btnEdit">Route bearbeiten</a>
          <a type="button" class="btn btn-success drawOption hidden" id="btnSave">Speichern</a>
          <a type="button" class="btn btn-danger drawOption hidden" id="btnCancel">Abbrechen</a>
          <div id="map" style="width:100%;height:550px;margin-top:10px"></div>
        </div>
      </div>
      <div class="row content">
        <div class="col-sm-12 col-sm-offset-0">
          <hr>
          <div>
            <h3>Delay Einstellungen</h3>
          </div>
          <form id="delayForm" class="form-inline" method="POST">
            <span>Delay [in Sekunden]</span>
            <input type="number" name="delay" class="form-control" min="1" max="1000" placeholder="5" id="txtDelay" required/>
            <input type="submit" class="btn btn-default" />
            <span id="divSubmit"></span>
          </form>
        </div>
      </div>
      <div class="row content">
        <div class="col-sm-12 col-sm-offset-0">
          <hr>
          <div>
            <h3>Benutzer Einstellungen</h3>
          </div>
        <div class="alert alert-danger hidden" id="errorMessage">
          <p></p>
        </div>
          <form id="userForm" class="form-inline" method="POST">
            <span>Neuen User anlegen: </span>
            <input type="text" name="username" class="form-control input-md" placeholder="Benutzername" required>
            <input type="password" name="password" class="form-control input-md" placeholder="Passwort" required>
            <input type="submit" class="btn btn-default" />
          </form>

          <div>
            <h3>Benutzer: </h3></div>
          <div id="userTable">

          </div>
        </div>
      </div>
    </div>

    <?php
    include "php/footer.php";
    ?>

    <script src="js/adminMaps.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBKiyusanWE3AYe4pMObUTGYhsnb45O2pI&callback=myMap&libraries=drawing"></script>

  </body>

  </html>