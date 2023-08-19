<?php
    class Connection {
        private static $instance = null;
        private $connection;

        private function __construct() {
            $host = "db";
            $username = "usuario";
            $password = "12345";
            $database = "AdminCS";

            $this->connection = new mysqli($host, $username, $password, $database);

            if ($this->connection->connect_error) {
                die("Error de conexión: " . $this->connection->connect_error);
            }
        }

        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new Connection();
            }
            return self::$instance;
        }

        public function getConnection() {
            return $this->connection;
        }
    }

?>