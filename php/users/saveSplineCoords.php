<?php
session_start(); 
if(!isset($_SESSION['username'])){     
    die("forbidden"); 
}
$data = $_POST['data'];
//set mode of file to writable.
chmod("../../splineCoords.txt",0777);
$f = fopen("../../splineCoords.txt", "w+") or die("fopen failed");
fwrite($f, $data);
fclose($f);
