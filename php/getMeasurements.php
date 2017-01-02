<?php
require_once 'Db.php';

$db = new Db();

$rows = $db->select("SELECT * FROM 
        (SELECT * FROM measurements ORDER BY time_measured desc LIMIT 20)test 
        ORDER BY time_measured;")
        or die(header("HTTP/1.1 500 Keine Messdaten vorhanden"));
echo json_encode($rows);        

