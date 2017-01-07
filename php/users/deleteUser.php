<?php
session_start();
if (!isset($_SESSION['username'])) {
    die("forbidden");
}
if (empty($_POST['id'])) {
    die(header("HTTP/1.1 500 id ist leer"));
}
require_once '../Db.php';
$db = new Db();
$userid = $db->quote($_POST['id']);
$rows = $db->query("DELETE FROM users WHERE ID=$userid;");
