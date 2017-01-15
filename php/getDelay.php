<?php
require_once 'MysqliDb.php';

try {
    $db = new MysqliDb();
    $row = $db->getOne("settings", "delay");

    $delay = $row['delay'];
    echo(json_encode($delay));
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    exit;
}