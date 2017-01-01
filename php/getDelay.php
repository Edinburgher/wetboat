<?php
require_once 'Db.php';

$db = new Db();

$rows = $db->select("SELECT delay FROM settings;");
$ret = $rows[0]['delay'];
echo (json_encode($ret));
