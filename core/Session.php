<?php
    namespace App\Core;

    class Session
    {
        private static ?Session $instance = null;

        private function __construct()
        {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }

        public static function getInstance(): Session
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function set(string $key, mixed $value): void
        {
            $_SESSION[$key] = $value;
        }

        public function get(string $key)
        {
            return $_SESSION[$key] ?? null;
        }

        public function unset(string $key): void
        {
            unset($_SESSION[$key]);
        }

        public function destroy(): void
        {
            $_SESSION = [];
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_destroy();
                self::$instance = null; 
            }
        }

        public function isset(string $key): bool{
            return isset($_SESSION[$key]);
        }
    }
