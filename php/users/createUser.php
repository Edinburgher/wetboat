<?php
session_start(); 
if(!isset($_SESSION['username']))
{     
    die("forbidden"); 
}
if(empty($_POST['username']) or empty($_POST['password']))
{
    die(header("HTTP/1.1 500 Benutzername oder Passwort leer"));
}
require_once '../Db.php';

$db = new Db();
$username = $db->quote($_POST['username']);
$password = $_POST['password'];
$rows = $db->select("SELECT * FROM users WHERE username=$username;")
        and die(header("HTTP/1.1 500 Benutzer existiert bereits"));

$hashAndSalt = password_hash($password, PASSWORD_BCRYPT);
$db->query("INSERT INTO users (username, hashed_password) VALUES ($username, '$hashAndSalt');");

