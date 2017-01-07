<?php

require_once 'WetboatDB.php';
$db = new WetboatDB();

$rows = $db->select("SELECT lat_user,lon_user FROM user_coords;") or die(header("HTTP/1.1 500 Keine Positionsdaten vorhanden"));
foreach ($rows as &$row) {
    $row = array($row['lat_user'], $row['lon_user']);;
}
echo(json_encode($rows));
