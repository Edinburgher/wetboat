<?php
session_start();
if (!isset($_SESSION['username'])) {
    die("forbidden");
}
if (empty($_POST['delay']) or !is_numeric($_POST['delay'])) {
    die(header("HTTP/1.1 500 Bitte geben Sie eine Zahl ein."));
}
$delay = $_POST['delay'];

require_once '../Db.php';
$db = new Db();
$db->quote($delay);
$db->query("UPDATE settings SET delay=$delay;");
echo $delay;

