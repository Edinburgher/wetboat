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


try {
    $db = new MysqliDb();
    $rows = $db->get("users");

    echo "<table class='table table-responsive table-bordered'>
    <tr>
    <th>ID</th>
    <th>username</th>
    <th>Löschen</th>
    </tr>";

    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
        if ($_SESSION['username'] === 'admin' and $_SESSION['username'] !== $row['username']) {
            echo "<td><a type='button' class='btn btn-danger' userid=" . htmlspecialchars($row['ID']) . ">Löschen</a></td>";
        } else {
            echo "<td>Mehr Rechte nötig</td>";
        }
        //echo "<td>" . $row['hashed_password'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    exit;
}
