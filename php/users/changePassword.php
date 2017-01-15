<?php
session_start();
require_once '../MysqliDb.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use Respect\Validation\Validator as v;

$vEmpty = v::not(v::notEmpty());
if ($vEmpty->validate($_SESSION['username'])) {
    header("HTTP/1.1 403 Verboten");
    echo '403 Verboten. Sie sind nicht eingeloggt';
    exit;
}
if ($vEmpty->validate($_POST['oldPassword']) or $vEmpty->validate($_POST['newPassword'])) {
    die(header("HTTP/1.1 500 Passwort darf nicht leer sein"));
}

$username = $_SESSION['username'];
$user_password_old = $_POST['oldPassword'];
$user_password_new = $_POST['newPassword'];
try {
    $db = new MysqliDb();
    $userdata = $db->where('username', $username)->getOne("users");
    $password_old = $userdata['hashed_password'];
    if (password_verify($user_password_old, $password_old)) {
        // Verified
        $hashAndSalt = password_hash($user_password_new, PASSWORD_BCRYPT);
        $db->where("username", $username)->update("users", array('hashed_password' => $hashAndSalt));

        echo 'Passwortänderung erfolgreich!';
    } else {
        header("HTTP/1.1 500 Falsches Passwort");
        exit;
    }
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    exit;
}