<?php
require_once 'Db.php';

$db = new Db();

$rows = $db->select("SELECT delay FROM settings;") or die(header("HTTP/1.1 500 Kein delay vorhanden"));
$ret = $rows[0]['delay'];
echo(json_encode($ret));
