<?php
session_start(); 
if(!isset($_SESSION['username'])){     
    die("forbidden"); 
}
if(empty($_POST['delay']))
{
    die(header("HTTP/1.1 500 delay ist leer"));
}
$delay = $_POST['delay'];
if (empty($delay)){
    header('Content-Type: text/plain; charset=utf-8');
    header("HTTP/1.1 500 Bitte geben Sie eine Zahl ein.");
    echo mysqli_error(); // Detailed error message in the response body
    die();
}

require_once '../Db.php';
$db = new Db();
$db->quote($delay);
$db->query("UPDATE settings SET delay=$delay;");
echo $delay;

