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
if ($vEmpty->validate($_POST['username']) or $vEmpty->validate($_POST['password'])) {
    header("HTTP/1.1 500 Benutzername oder Passwort leer");
    exit;
}

if (!v::notEmpty()->alnum()->length(1, 40)->validate($_POST['username'])) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "Benutzername ungültig. Nur Buchstaben von A bis Z und Zahlen zulässig.";
    exit;
}

$db = new MysqliDb();
$username = $_POST['username'];
$password = $_POST['password'];

try {
    $db = new MysqliDb();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userdata = $db->where('username', $username)->getOne("users");
    if($db->getLastErrno()!== 0){
        header("HTTP/1.1 500 Benutzer existiert bereits");
        exit;
    }
    $hashAndSalt = password_hash($user_password_new, PASSWORD_BCRYPT);
    $db->insert("users", array('username' => $username, 'hashed_password' => $hashAndSalt));
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    exit;
}
