<?php
require_once 'WetboatDB.php';

$db = new WetboatDB();

$rows = $db->select("SELECT * FROM `V_NEWEST_MEASUREMENT`;") or die(header("HTTP/1.1 500 Keine Messdaten vorhanden"));
echo(json_encode($rows[0]));
