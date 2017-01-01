<?php
session_start(); 
if(!isset($_SESSION['username']))
{     
    die("forbidden"); 
}

require_once '../Db.php';

$db = new Db();
$username = $db->quote($_POST['username']);
$password = $_POST['password'];
$rows = $db->select("SELECT * FROM users WHERE username=$username;");
if($rows)
{
    header('Content-Type: text/plain; charset=utf-8');
    header("HTTP/1.1 500 Benutzer existiert bereits");
    echo mysqli_error($conn); // Detailed error message in the response body
    die();
}
$hashAndSalt = password_hash($password, PASSWORD_BCRYPT);
$db->query("INSERT INTO users (username, hashed_password) VALUES ($username, '$hashAndSalt');");

