<?php
namespace Helpers;

use PDO;
use PDOException;

class DBConnection
{
    private $_connection;
    private static $_instance; //The single instance
    private $_host = "localhost";
    private $_username = "root";
    private $_password = "kaikai";
    private $_database = "drive";


    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {

        try {
            $this->_connection = new PDO(
                'mysql:host=' . $this->_host . ';dbname=' . $this->_database,
                $this->_username,
                $this->_password
            );

            $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }
    }

    private function __clone()
    {
    }

    public function getConnection()
    {
        return $this->_connection;
    }
}
