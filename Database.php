<?php

namespace Core;

use PDO;

class Database
{
    public PDO $pdo;
    private static ?Database $instance = null;

    private function __construct()
    {
        $config = require_once Application::$rootDir.'/config/database.php';
        $dsn = $config['dsn'];
        $user = $config['user'];
        $password = $config['password'];
        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance(): Database
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}