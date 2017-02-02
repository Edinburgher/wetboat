<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <?php
    include_once __DIR__ . "/php/blocks/head.php";
    ?>
    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
    <script src="js/delay.js"></script>
    <script src="js/funcMaps.js"></script>
    <script src="js/userMaps.js"></script>
    <?php
    include_once __DIR__ . "/php/blocks/maps.php";
    ?>
    <script src="js/diagramm.js"></script>
    <script src="js/index.js"></script>

    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <?php
    include_once __DIR__ . "/php/blocks/nav.php";
    ?>
    <div class="container-fluid">
        <div class="row content">
            <div class="col-sm-5 col-sm-offset-0">
                <img id="liveImage" src="img/0.jpg?<?php echo filemtime("img/0.jpg") ?>" class="img-responsive"/>
                <br>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Letzte Messung</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Zeit</th>
                                    <th class="text-center">Luft [°C]</th>
                                    <th class="text-center">Wasser [°C]</th>
                                    <th class="text-center">Windgeschw. [km/h]</th>
                                    <th class="text-center">Luftdruck [hPa]</th>
                                    <th class="text-center">Luftfeuchte [%]</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td id="time"></td>
                                    <td id="airtemp"></td>
                                    <td id="watertemp"></td>
                                    <td id="windspeed"></td>
                                    <td id="airpressure"></td>
                                    <td id="airhumidity"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-7">
                <div id="container"></div>
            </div>
        </div>
        <div class="row content">
            <div class="col-sm-12 col-sm-offset-0">
                <hr>
                <div id="map"></div>
            </div>
        </div>
    </div>
    <?php
    include_once __DIR__ . "/php/blocks/footer.php";
    ?>

</body>

</html>