<?php

if (empty($_POST['lastModified']) or !is_numeric($_POST['lastModified'])) {
    die(header("HTTP/1.1 500 lastModified is not a number"));
}

$lastModified = $_POST['lastModified'];
$newModified = filemtime($_SERVER['DOCUMENT_ROOT'] . "/img/0.jpg");
if ($lastModified != $newModified) {
    echo $newModified;
}