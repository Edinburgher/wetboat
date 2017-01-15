<?php
require_once 'MysqliDb.php';

try {
    $db = new MysqliDb();
    $rows = $db->orderBy("time_measured", "DESC")->get("measurements", 20);

    //the latest 20 measurements, but sorted ASC
    $measurements = array_reverse($rows);
    echo json_encode($measurements);
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    exit;
}
