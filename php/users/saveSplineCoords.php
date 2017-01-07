<?php
session_start();
if (!isset($_SESSION['username'])) {
    die("forbidden");
}
if (empty($_POST['data'])) {
    die(header("HTTP/1.1 500 data ist leer"));
}
$data = $_POST['data'];
//set mode of file to writable.
chmod("../../splineCoords.txt", 0777);
$f = fopen("../../splineCoords.txt", "w+") or die("fopen failed");
fwrite($f, $data);
fclose($f);
