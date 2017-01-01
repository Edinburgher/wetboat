<?php
session_start(); 
if(!isset($_SESSION['username'])){     
    die("forbidden"); 
}

require_once '../Db.php';
$db = new Db();

$userCoords = json_decode($_POST['userCoords']);

//echo json_encode($userCoords);

//http://stackoverflow.com/questions/7746720/inserting-a-multi-dimensional-php-array-into-a-mysql-database
$data = array();
foreach($userCoords as $row) {
    $lat = $row->lat;
    $lon = $row->lng;
    //echo $lat.", ".$lon."\n";
    $data[] = "($lat, $lon)";
}

$values = implode(',', $data);
//echo "INSERT INTO user_coords (lat_user, lon_user) VALUES $values;";
$sql = "DELETE FROM user_coords; ";
$sql.= "ALTER TABLE user_coords AUTO_INCREMENT = 1; ";
$sql.= "INSERT INTO user_coords (lat_user, lon_user) VALUES $values;";

$db->multi_query($sql);

