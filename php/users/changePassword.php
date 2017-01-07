<?php
session_start();
if (!isset($_SESSION['username'])) {
    die("forbidden");
}
if (empty($_POST['oldPassword']) or empty($_POST['newPassword'])) {
    die(header("HTTP/1.1 500 Passwort darf nicht leer sein"));
}

$user_password_old = $_POST['oldPassword'];
$user_password_new = $_POST['newPassword'];

require_once '../Db.php';

$db = new Db();
$username = $db->quote($_SESSION['username']);

$rows = $db->select("SELECT * FROM users WHERE username=$username;")
or die(header("HTTP/1.1 500 Benutzer existiert nicht"));
$password_old = $rows[0]['hashed_password'];
if (password_verify($user_password_old, $password_old)) {
    // Verified
    $hashAndSalt = password_hash($user_password_new, PASSWORD_BCRYPT);
    $db->query("UPDATE users SET hashed_password='$hashAndSalt' where username=$username;");
} else {
    die(header("HTTP/1.1 500 Falsches Passwort"));
}

echo 'Passwortänderung erfolgreich!';