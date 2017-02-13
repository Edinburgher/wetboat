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
    private $username = null, $hashed_password = null, $conn = null, $vEmpty = null;

    /**
     * UserAction constructor.
     */
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->vEmpty = v::not(v::notEmpty());
        $_SESSION['username'] = null;
        if(empty($_SESSION['username'])){
            $this->username = $_SESSION['username'];
        }
        $this->conn = new MysqliDb();
        $this->hashed_password = $this->conn->where("username", $this->username)->getOne("users")['hashed_password'];
    }

    /**
     * Checks if user is logged in (session)
     * Echos error code 500 if not.
     *
     * @return void
     */
    private function checkLoggedIn()
    {
        if ($this->vEmpty->validate($_SESSION['username'])) {
            header("HTTP/1.1 403 Verboten");
            echo '403 Verboten. Sie sind nicht eingeloggt';
            exit;
        }
    }

    /**
     * Changes password in database if old password is correct
     * Echos 500 error if not.
     *
     * @return void
     */
    public function changePassword()
    {
        $this->checkLoggedIn();
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
        }
        catch (Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }
    }

    /**
     * Creates user in database if username doesnt exist
     * Echos error code 500 if it does.
     *
     * @return void
     */
    public function createUser()
    {
        $this->checkLoggedIn();
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
        }
        catch (Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }
    }

    /**
     * Deletes user from database if username exists
     * checks for username specifications (alphanumeric, length = 1 ... 40)
     * Echos error code 500 if it doesn't.
     *
     * @return void
     */
    public function deleteUser()
    {
        $this->checkLoggedIn();
        if ($this->vEmpty->validate($_POST['id'])) {
            header("HTTP/1.1 500 empty id");
            echo "id ist leer";
            exit;
        }
        try {
            $userid = $_POST['id'];
            $this->conn->where("ID", $userid)->delete("users");
        }
        catch (Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }
    }

    /**
     * Gets users from database
     * Echos error code 500 if it does.
     *
     * @return void
     */
    public function getUsers()
    {
        $this->checkLoggedIn();
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
        }
        catch (Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }

    }

    /**
     * Saves spline coordinates to splineCoords.txt
     * Echos error if it fails.
     *
     * @return void
     */
    public function saveSplineCoords()
    {
        $this->checkLoggedIn();
        if ($this->vEmpty->validate($_POST['splineCoords'])) {
            header("HTTP/1.1 500 data empty");
            echo "data ist leer";
            exit;
        }
        $splineCoords = $_POST['splineCoords'];
        $filename = $_SERVER['DOCUMENT_ROOT'] . "/splineCoords.txt";
        //set mode of file to writable.
        chmod("$filename", 0777);
        $f = fopen($filename, "w+") or die("fopen failed");
        fwrite($f, $splineCoords);
        fclose($f);
    }

    /**
     * Saves user input coordinates to database
     * Echos error code 500 if points aren't set
     *
     * @return void
     */
    public function saveUserCoords()
    {
        $this->checkLoggedIn();
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

    /**
     * Sets delay in database settings table
     * Echos error code 500 if it fails.
     *
     * @return void
     */
    public function setDelay()
    {
        $this->checkLoggedIn();
        if ($this->vEmpty->validate($_POST['delay']) or !v::numeric()->validate($_POST['delay'])) {
            header("HTTP/1.1 500 Not Numeric");
            echo "Bitte geben Sie eine Zahl ein.";
            exit;
        }
        $delay = $_POST['delay'];
        $this->conn->update("settings", array("delay" => $delay));
        echo $delay;
    }

    /**
     * Logs in user
     * Echos error code 500 if it fails.
     *
     * @return void
     */
    public function login()
    {
        if (!v::notEmpty()->alnum()->length(1, 40)->validate($_POST['username'])) {
            header("HTTP/1.1 500 username invalid");
            echo "Benutzername ungültig. Nur Buchstaben von A bis Z und Zahlen zulässig.";
            exit;
        }

        if ($this->vEmpty->validate(($_POST['password']))) {
            header("HTTP/1.1 500 empty password");
            echo "Passwort leer";
            exit;
        }
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            $userdata = $this->conn->where('username', $username)->getOne("users");

            $hashAndSalt = $userdata['hashed_password'];
            if (password_verify($password, $hashAndSalt)) {
                // Verified
                $_SESSION['username'] = $userdata['username'];
                echo "Anmeldung erfolgreich, " . $_SESSION['username'] . "!";
            } else {
                header("HTTP/1.1 500 wrong password or username");
                echo "Benutzername oder Passwort falsch";
                exit;
            }
        }
        catch (Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }

    }

    /**
     * Logs out user
     *
     * @return void
     */
    public static function logout()
    {
        session_start();
        session_destroy();
        echo "Abmeldung erfolgreich";
        header("Location: /");
    }

    /**
     * Gets delay from database settings table
     * Echos error code 500 if it fails.
     *
     * @return void
     */
    public function getDelay()
    {
        try {
            $row = $this->conn->getOne("settings", "delay");
            $delay = $row['delay'];
            echo(json_encode($delay));
        }
        catch (Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }
    }

    /**
     * Gets specific number of measurments from database
     * Echos error code 500 if it fails.
     *
     * @return void
     */
    public function getNewestMeasurement()
    {
        try {
            $row = $this->conn->orderBy("time_measured", "DESC")->getOne("measurements");
            echo json_encode($row);
        }
        catch (Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }
    }

    /**
     * Gets newest measurment from database
     * Echos error code 500 if it fails.
     *
     * @return void
     */
    public function getMeasurements()
    {
        try {
            $rows = $this->conn->orderBy("time_measured", "DESC")->get("measurements", 2000);
            //the latest 20 measurements, but sorted ASC
            $measurements = array_reverse($rows);
            echo json_encode($measurements);
        }
        catch (Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }
    }

    /**
     * Checks if old timestamp < new timestamp of image file (0.jpg)     *
     * Echos error code 500 if it fails.
     * Echos new timestamp
     *
     * @return void
     */
    public function getNewLiveImg()
    {
        if (!v::notEmpty()->numeric()->validate($_POST['lastModified'])) {
            header("HTTP/1.1 500 lastModified is not a number");
            exit;
        }

        $lastModified = $_POST['lastModified'];
        $newModified = filemtime($_SERVER['DOCUMENT_ROOT'] . "/img/0.jpg");
        if ($lastModified != $newModified) {
            echo $newModified;
        }
    }

    /**
     * Gets user input coordinates from database
     * Echos error code 500 if it fails.
     *
     * @return void
     */
    public function getUserCoords()
    {
        try {
            $rows = $this->conn->get("user_coords", null, array('lat_user', 'lon_user'));
            foreach ($rows as &$row) {
                $row = array($row['lat_user'], $row['lon_user']);;
            }
            echo json_encode($rows);
        }
        catch (Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }
    }
}