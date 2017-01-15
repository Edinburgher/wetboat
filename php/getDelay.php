<?php
require_once 'MysqliDb.php';
use Respect\Validation\Validator as v;

$db2 = new MysqliDb();
$delay = $db2->getOne("settings", "delay")['delay'];
echo(json_encode($delay));

