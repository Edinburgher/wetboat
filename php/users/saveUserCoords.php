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
if ($vEmpty->validate($_POST['userCoords'])) {
    header("HTTP/1.1 500 userCoords empty");
    echo "userCoords ist leer";
    exit;
}

$db = new MysqliDb();

$userCoords = json_decode($_POST['userCoords']);

//http://stackoverflow.com/questions/7746720/inserting-a-multi-dimensional-php-array-into-a-mysql-database
$data = array();
foreach ($userCoords as $row) {
    $data[] = array($row->lat, $row->lng);
}

$db->delete("user_coords");
$db->rawQuery("ALTER TABLE user_coords AUTO_INCREMENT = 1;");
$db->insertMulti("user_coords", $data, array("lat_user", "lon_user"));

