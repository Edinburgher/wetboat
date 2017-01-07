<?php
require_once 'WetboatDB.php';

$db = new WetboatDB();

$rows = $db->select("SELECT * FROM 
        (SELECT * FROM measurements ORDER BY time_measured DESC LIMIT 20)test 
        ORDER BY time_measured;") or die(header("HTTP/1.1 500 Keine Messdaten vorhanden"));
echo json_encode($rows);        

