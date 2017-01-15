<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use Respect\Validation\Validator as v;

if (!v::notEmpty()->numeric()->validate($_POST['lastModified'])) {
    header("HTTP/1.1 500 lastModified is not a number");
    exit;
}

$lastModified = $_POST['lastModified'];
$newModified = filemtime($_SERVER['DOCUMENT_ROOT'] . "/img/0.jpg");
if ($lastModified != $newModified) {
    echo $newModified;
}