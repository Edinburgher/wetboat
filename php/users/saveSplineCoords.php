<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use Respect\Validation\Validator as v;

$vEmpty = v::not(v::notEmpty());
if ($vEmpty->validate($_SESSION['username'])) {
    header("HTTP/1.1 403 Verboten");
    echo '403 Verboten. Sie sind nicht eingeloggt';
    exit;
}
if ($vEmpty->validate($_POST['data'])) {
    die(header("HTTP/1.1 500 data ist leer"));
}
$data = $_POST['data'];
//set mode of file to writable.
chmod("../../splineCoords.txt", 0777);
$f = fopen("../../splineCoords.txt", "w+") or die("fopen failed");
fwrite($f, $data);
fclose($f);
