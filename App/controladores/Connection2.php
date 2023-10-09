<?php
    class Connection2 {
        private static $instance = null;
        private $connection;

        private function __construct() {
            $host = "db2";
            $username = "usuario";
            $password = "12345";
            $database = "PruebasAppCS";

            $this->connection = new mysqli($host, $username, $password, $database);

            if ($this->connection->connect_error) {
                die("Error de conexión: " . $this->connection->connect_error);
            }

            // Configurar la codificación de caracteres a UTF-8
            $this->connection->set_charset("utf8");
        }

        public static function getInstance2() {
            if (self::$instance == null) {
                self::$instance = new Connection2();
            }
            return self::$instance;
        }

        public function getConnection2() {
            return $this->connection;
        }
    }

?>