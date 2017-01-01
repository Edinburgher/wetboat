<?php
session_start();

require_once 'Db.php';

$db = new Db();
$username = $db->quote($_POST['username']);
$password = $_POST['password'];
$rows = $db->select("SELECT * FROM users WHERE username=$username;");

if (!$rows)
{
    header('Content-Type: text/plain; charset=utf-8');
    header("HTTP/1.1 500 Benutzer existiert nicht");
    echo $db->error(); // Detailed error message in the response body
    die();
} 
else 
{
    $userdata = $rows[0];
    $hashAndSalt = $userdata['hashed_password'];
    if (password_verify($password, $hashAndSalt)) 
    {
        // Verified
        $_SESSION['username'] = $userdata['username'];
        echo "Anmeldung erfolgreich, ".$_SESSION['username']."!";
    } 
    else 
    {
        header('Content-Type: text/plain; charset=utf-8');
        header("HTTP/1.1 500 Falsches Passwort");
        echo  $db->error(); // Detailed error message in the response body
        die();
    }
}
