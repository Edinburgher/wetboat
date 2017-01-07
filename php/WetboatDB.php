<?php

//https://www.binpress.com/tutorial/using-php-with-mysql-the-right-way/17
class WetboatDB
{
    // The database connection
    protected static $connection;

    /**
     * Connect to the database
     *
     * @return bool|mysqli false on failure / mysqli MySQLi object instance on success
     */
    private function connect()
    {
        // Try and connect to the database
        if (!isset(self::$connection)) {
            // Load configuration as an array. Use the actual location of your configuration file
            require_once $_SERVER["DOCUMENT_ROOT"] . '/php/config.php';

            //http://stackoverflow.com/questions/15553496/new-mysqli-how-to-intercept-an-unable-to-connect-error
            mysqli_report(MYSQLI_REPORT_STRICT);
            try {
                self::$connection = new mysqli($host, $user, $pwd, $db);
            } catch (Exception $e) {
                die(header("HTTP/1.1 500 SQL Server offline"));
            }

        }

        // If connection was not successful, handle the error
        if (self::$connection === false) {
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
        }
        return self::$connection;
    }

    /**
     * Query the database
     *
     * @param string $query The query string
     * @return mixed The result of the mysqli::query() function
     */
    public function query($query)
    {
        // Connect to the database
        $connection = $this->connect();

        // Query the database
        $result = $connection->query($query);

        return $result;
    }

    public function multi_query($query)
    {
        // Connect to the database
        $connection = $this->connect();

        // Query the database
        $result = $connection->multi_query($query);

        return $result;
    }

    /**
     * Fetch rows from the database (SELECT query)
     *
     * @param string $query The query string
     * @return bool|array False on failure / array Database rows on success
     */
    public function select($query)
    {
        $rows = array();
        $result = $this->query($query);
        if ($result === false) {
            return false;
        }
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Fetch the last error from the database
     *
     * @return string Database error message
     */
    public function error()
    {
        $connection = $this->connect();
        return $connection->error;
    }

    /**
     * Quote and escape value for use in a database query
     *
     * @param string $value The value to be quoted and escaped
     * @return string The quoted and escaped string
     */
    public function quote($value)
    {
        $connection = $this->connect();
        return "'" . $connection->real_escape_string($value) . "'";
    }
}