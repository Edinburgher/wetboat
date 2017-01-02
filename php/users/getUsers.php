<?php
session_start();
if(!isset($_SESSION['username'])){
    die("forbidden");
}

require_once '../Db.php';
$db = new Db();
$rows = $db->select("SELECT * FROM users;")
        or die(header("HTTP/1.1 500 Keine user vorhanden"));;

echo "<table class='table table-responsive table-bordered'>
<tr>
<th>ID</th>
<th>username</th>
<th>Löschen</th>
</tr>";

foreach($rows as $row){
    echo "<tr>";
    echo "<td>" . $row['ID'] . "</td>";
    echo "<td>" . $row['username'] . "</td>";
    if ($_SESSION['username'] === 'admin' and $_SESSION['username'] !== $row['username'])
    {
        echo "<td><a type='button' class='btn btn-danger' userid=".$row['ID'].">Löschen</a></td>";
    }
    else
    {
        echo "<td>Mehr Rechte nötig</td>";
    }
    //echo "<td>" . $row['hashed_password'] . "</td>";
    echo "</tr>";
}
echo "</table>";
