<?php
require_once 'MysqliDb.php';

try {
    $db = new MysqliDb();
    $row = $db->orderBy("time_measured", "DESC")->getOne("measurements");

    echo json_encode($row);
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    exit;
}
