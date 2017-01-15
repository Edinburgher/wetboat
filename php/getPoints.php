<?php
require_once 'MysqliDb.php';

try {
    $db = new MysqliDb();
    $rows = $db->get("user_coords", null, array('lat_user', 'lon_user'));
    foreach ($rows as &$row) {
        $row = array($row['lat_user'], $row['lon_user']);;
    }

    echo json_encode($rows);
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    exit;
}
