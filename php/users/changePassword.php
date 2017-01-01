<?php
session_start();
if(!isset($_SESSION['username']))
{
    die("forbidden");
}

$user_password_old = $_POST['oldPassword'];
$user_password_new = $_POST['newPassword'];

require_once '../Db.php';

$db = new Db();
$username = $db->quote($_SESSION['username']);

$rows = $db->select("SELECT * FROM users WHERE username=$username;");
$password_old = $rows[0]['hashed_password'];
if (password_verify($user_password_old, $password_old))
{
    // Verified
    $hashAndSalt = password_hash($user_password_new, PASSWORD_BCRYPT);
    $db->query("UPDATE users SET hashed_password='$hashAndSalt' where username=$username;");
}
else
{
    header('Content-Type: text/plain; charset=utf-8');
    header("HTTP/1.1 500 Falsches Passwort");
    echo  $db->error(); // Detailed error message in the response body
    die();
}

echo 'Passwortänderung erfolgreich!';