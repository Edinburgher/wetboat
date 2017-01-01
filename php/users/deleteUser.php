<?php
session_start();
if(!isset($_SESSION['username']))
{
    die("forbidden");
}

require_once '../Db.php';
$db = new Db();
$userid = $db->quote($_POST['id']);
$rows = $db->query("DELETE FROM users WHERE ID=$userid;");
