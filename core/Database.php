<?php
class Database {
    private static $connection;

    public static function getConnection() {
        if (self::$connection === null) {
            self::$connection = new mysqli('localhost', 'root', '', 'auth_system');
            if (self::$connection->connect_error) {
                die('Connection failed: ' . self::$connection->connect_error);
            }
        }
        return self::$connection;
    }
}
?>
