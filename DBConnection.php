<?php

class DBConnection {
    private $_connection;
    private static $_instance; //The single instance
    private $_host = "localhost";
    private $_username = "root";
    private $_password = "kaikai";
    private $_database = "drive";


    public static function getInstance() {
        if(!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    // Constructor
    public function __construct() {
        $this->_connection = new PDO(
            'mysql:host='.$this->_host.';dbname='.$this->_database,
            $this->_username,
            $this->_password
            );

        // Error handling
//        if(mysqli_connect_error()) {
//            trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
//                E_USER_ERROR);
//        }
    }
    // Magic method clone is empty to prevent duplication of connection
    private function __clone() { }
    // Get mysqli connection
    public function getConnection() {
        return $this->_connection;
    }
}
