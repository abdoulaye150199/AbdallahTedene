<?php

    namespace App\Core;

    class Database {
        private $connection;
        private static $instance = null;

        private function __construct() {
            $user = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASSWORD'];

            try {
                $this->connection = new \PDO("pgsql:host=". $_ENV['DB_HOST']. ";port= ".$_ENV['DB_PORT'].";dbname=maxitdb", $user, $password);
            } catch (\PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }

        public static function getInstance() {
            if (self::$instance === null) {
                self::$instance = new Database();
            }
            return self::$instance;
        }

        public function getConnection(): \PDO {
            return $this->connection;
        }
    }
