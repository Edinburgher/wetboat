<?php

require_once 'Db.php';
$db = new Db();

$rows = $db->select("SELECT lat_user,lon_user FROM user_coords;");
foreach ($rows as &$row) {
    $row = array($row['lat_user'], $row['lon_user']);;
}
echo (json_encode($rows));
