<?php
session_start();
if (!isset($_SESSION['username'])) {
    die("forbidden");
}
if (empty($_POST['id'])) {
    die(header("HTTP/1.1 500 id ist leer"));
}
require_once '../WetboatDB.php';
$db = new WetboatDB();
$userid = $db->quote($_POST['id']);
$rows = $db->query("DELETE FROM users WHERE ID=$userid;");
