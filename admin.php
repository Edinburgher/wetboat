<?php
session_start();
if(!isset($_SESSION['username'])){
  header("Location:login.php");
}
?>

<!DOCTYPE html>
<html lang="de">

  <head>
    <?php
      include_once "php/head.php";
    ?>

    <script src="js/delay.js"></script>
    <script src="js/funcMaps.js"></script>
    <script src="js/admin.js"></script>
    <link rel="stylesheet" href="css/main.css">
  </head>

  <body>
    <?php
      include_once "php/nav.php";
    ?>

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
          <span>Delay [in Sekunden]:</span>
          <form id="delayForm" class="form-inline" method="POST">
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
          <div class="alert alert-danger hidden" id="alertUserForm">
            <p></p>
          </div>
          <span>Neuen User anlegen:</span>
          <form id="userForm" class="form-inline" method="POST">
            <input type="text" name="username" class="form-control input-md" placeholder="Benutzername" required>
            <input type="password" name="password" class="form-control input-md" placeholder="Passwort" required>
            <input type="submit" class="btn btn-default" />
          </form>
          <br>
          <div class="alert hidden" id="alertChangePwdForm">
            <p></p>
          </div>
          <span>Passwort ändern:</span>
          <form id="changePwdForm" class="form-inline" method="POST">
            <input type="password" name="oldPassword" class="form-control input-md" placeholder="Altes Passwort" required>
            <input type="password" name="newPassword" class="form-control input-md" placeholder="Neues Passwort" required>
            <input type="submit" class="btn btn-default" />
          </form>

          <div><h3>Benutzer: </h3></div>
          <div id="userTable"></div>
        </div>
      </div>
    </div>

    <?php
      include_once "php/footer.php";
    ?>

    <script src="js/adminMaps.js"></script>

    <?php
      include_once "php/maps.php";
    ?>
  </body>

</html>