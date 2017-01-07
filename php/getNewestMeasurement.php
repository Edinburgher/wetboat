<?php
require_once 'Db.php';

$db = new Db();

$rows = $db->select("SELECT * FROM `V_NEWEST_MEASUREMENT`;") or die(header("HTTP/1.1 500 Keine Messdaten vorhanden"));
echo(json_encode($rows[0]));
