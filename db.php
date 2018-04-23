<?php

$config = require_once 'config.php';

class DB {

    private static $instance = null;

    private $pdo = null;

    public static function getInstance() {
        return is_null(self::$instance) ?  (new self)->pdo : self::$instance->pdo;
    }

    private  function __construct() {
        $this->pdo = new PDO(sprintf('mysql:host=%s;dbname=%s', DB_HOST, DB_NAME), DB_USER, DB_PASSWORD);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function __clone() {}

    private function __wakeup() {}
}