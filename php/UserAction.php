<?php
/**
 * Created by PhpStorm.
 * User: Mario
 * Date: 01.02.2017
 * Time: 09:06
 */

require_once 'MysqliDb.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use Respect\Validation\Validator as v;

class UserAction
{
    private $username, $hashed_password, $conn, $vEmpty;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->vEmpty = $vEmpty = v::not(v::notEmpty());
        $this->checkLoggedIn();
        $this->username = $_SESSION['username'];
        $this->conn = new MysqliDb();
        $this->hashed_password = $this->conn->where("username", $this->username)->getOne("users")['hashed_password'];
    }

    private function checkLoggedIn()
    {
        if ($this->vEmpty->validate($_SESSION['username'])) {
            header("HTTP/1.1 403 Verboten");
            echo '403 Verboten. Sie sind nicht eingeloggt';
            exit;
        }
    }

    public function changePassword()
    {
        if ($this->vEmpty->validate($_POST['oldPassword']) or $this->vEmpty->validate($_POST['newPassword'])) {
            header("HTTP/1.1 500 Password empty");
            echo "Passwort darf nicht leer sein";
            exit;
        }

        $user_password_old = $_POST['oldPassword'];
        $user_password_new = $_POST['newPassword'];
        try {
            if (password_verify($user_password_old, $this->hashed_password)) {
                // Verified
                $hashAndSalt = password_hash($user_password_new, PASSWORD_BCRYPT);
                $this->conn->where("username", $this->username)->update("users", array('hashed_password' => $hashAndSalt));

                echo 'Passwortänderung erfolgreich!';
            } else {
                header("HTTP/1.1 500 Wrong Password");
                echo "Falsches Passwort";
                exit;
            }
        } catch (Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }
    }

    public function createUser()
    {
        if ($this->vEmpty->validate($_POST['username']) or $this->vEmpty->validate($_POST['password'])) {
            header("HTTP/1.1 500 Benutzername oder Passwort leer");
            exit;
        }

        if (!v::notEmpty()->alnum()->length(1, 40)->validate($_POST['username'])) {
            header("HTTP/1.1 500 username invalid");
            echo "Benutzername ungültig. Nur Buchstaben von A bis Z und Zahlen zulässig.";
            exit;
        }
        try {
            $newUsername = $_POST['username'];
            $newPassword = $_POST['password'];
            $userdata = $this->conn->where('username', $newUsername)->getOne("users");
            if ($userdata) {
                header("HTTP/1.1 500 user already exists");
                echo "Benutzer existiert bereits";
                exit;
            }
            $hashAndSalt = password_hash($newPassword, PASSWORD_BCRYPT);
            $this->conn->insert("users", array('username' => $newUsername, 'hashed_password' => $hashAndSalt));
        } catch (Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }
    }

    public function deleteUser()
    {
        if ($this->vEmpty->validate($_POST['id'])) {
            header("HTTP/1.1 500 empty id");
            echo "id ist leer";
            exit;
        }
        try {
            $userid = $_POST['id'];
            $this->conn->where("ID", $userid)->delete("users");
        } catch (Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }
    }

    public function getUsers()
    {
        $rows = $this->conn->get("users");
        try {
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

    }

    public function saveSplineCoords()
    {
        if ($this->vEmpty->validate($_POST['splineCoords'])) {
            header("HTTP/1.1 500 data empty");
            echo "data ist leer";
            exit;
        }
        $data = $_POST['data'];
        $filename = $_SERVER['DOCUMENT_ROOT'] . "/splineCoords.txt";
        //set mode of file to writable.
        chmod($filename, 0777);
        $f = fopen($filename, "w+") or die("fopen failed");
        fwrite($f, $data);
        fclose($f);
    }

    public function saveUserCoords()
    {
        if ($this->vEmpty->validate($_POST['userCoords'])) {
            header("HTTP/1.1 500 userCoords empty");
            echo "userCoords ist leer";
            exit;
        }
        $userCoords = json_decode($_POST['userCoords']);

        //http://stackoverflow.com/questions/7746720/inserting-a-multi-dimensional-php-array-into-a-mysql-database
        $data = array();
        foreach ($userCoords as $row) {
            $data[] = array($row->lat, $row->lng);
        }

        $this->conn->delete("user_coords");
        $this->conn->rawQuery("ALTER TABLE user_coords AUTO_INCREMENT = 1;");
        $this->conn->insertMulti("user_coords", $data, array("lat_user", "lon_user"));
    }

    public function setDelay()
    {
        if ($this->vEmpty->validate($_POST['delay']) or !v::numeric()->validate($_POST['delay'])) {
            header("HTTP/1.1 500 Not Numeric");
            echo "Bitte geben Sie eine Zahl ein.";
            exit;
        }
        $delay = $_POST['delay'];
        $this->conn->update("settings", array("delay" => $delay));
        echo $delay;
    }

}