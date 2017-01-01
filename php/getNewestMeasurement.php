<?php
require_once 'Db.php';

$db = new Db();

$rows = $db->select("SELECT * FROM `V_NEWEST_MEASUREMENT`;");
echo (json_encode($rows[0]));
