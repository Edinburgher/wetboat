<?php
session_start();
if (empty($_POST['username']) or empty($_POST['password'])) {
    die(header("HTTP/1.1 500 Benutzername oder Passwort leer"));
}

require_once 'WetboatDB.php';

$db = new WetboatDB();

$username = $db->quote($_POST['username']);
$password = $_POST['password'];
$rows = $db->select("SELECT * FROM users WHERE username=$username;") or die(header("HTTP/1.1 500 Benutzer existiert nicht"));

$userdata = $rows[0];
$hashAndSalt = $userdata['hashed_password'];
if (password_verify($password, $hashAndSalt)) {
    // Verified
    $_SESSION['username'] = $userdata['username'];
    echo "Anmeldung erfolgreich, " . $_SESSION['username'] . "!";
} else {
    die(header("HTTP/1.1 500 Falsches Passwort"));
}