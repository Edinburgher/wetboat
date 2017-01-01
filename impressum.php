<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">

  <head>
    <?php
      include "php/head.php";
    ?>
    
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css">
  </head>

  <body>
    <?php
      include "php/nav.php";
    ?>
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