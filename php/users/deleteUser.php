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
if ($vEmpty->validate($_POST['id'])) {
    die(header("HTTP/1.1 500 id ist leer"));
}

try {
    $db = new MysqliDb();
    $userid =$_POST['id'];
    $row = $db->where("ID", $userid)->delete("users");
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    exit;
}
