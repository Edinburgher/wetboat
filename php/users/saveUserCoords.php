<?php
session_start(); 
if(!isset($_SESSION['username'])){     
    die("forbidden"); 
}
if(empty($_POST['userCoords']))
{
    die(header("HTTP/1.1 500 userCoords ist leer"));
}
require_once '../Db.php';
$db = new Db();

$userCoords = json_decode($_POST['userCoords']);

//http://stackoverflow.com/questions/7746720/inserting-a-multi-dimensional-php-array-into-a-mysql-database
$data = array();
foreach($userCoords as $row) {
    $lat = $row->lat;
    $lon = $row->lng;
    $data[] = "($lat, $lon)";
}

$values = implode(',', $data);
$sql = "DELETE FROM user_coords; ";
$sql.= "ALTER TABLE user_coords AUTO_INCREMENT = 1; ";
$sql.= "INSERT INTO user_coords (lat_user, lon_user) VALUES $values;";

$db->multi_query($sql);

