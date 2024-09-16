<?php
namespace App\Database;

class DB
{
    const HOST = 'localhost';
    const USER = 'root';
    const PASSWORD = null;
    const DATABASE = 'citiesapi';

    protected $mysqli;

    function __construct($host = self::HOST, $user = self::USER, $password = self::PASSWORD, $database = self::DATABASE) {
        $this->mysqli = mysqli_connect(hostname: $host, username: $user, password: $password, database: $database);
        if (!$this->mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $this->mysqli->set_charset(charset: "utf8mb4");
    }

    function __destruct() {
        $this->mysqli->close();
    }
}