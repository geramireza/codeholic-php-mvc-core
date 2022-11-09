<?php

namespace Core;

class Migration
{
    public string $migrationDir;
    public Database $db;
    public string $namespace = "\Database\Migrations";

    public function __construct()
    {
        $this->migrationDir = Application::$rootDir . '/database/migrations';
//        parent::__construct($config);
        $this->db = Database::getInstance();
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $applyingMigrations = scandir($this->migrationDir);
        $toApplyMigrations = array_diff($applyingMigrations, $appliedMigrations);

        $migrated = [];
        foreach ($toApplyMigrations as $toApplyMigration) {
            if ($toApplyMigration === '.' || $toApplyMigration === '..') {
                continue;
            }
            require_once $this->migrationDir . "/" . $toApplyMigration;
            $fileName = pathinfo($toApplyMigration, PATHINFO_FILENAME);
            $className = str_replace('_', '', ucwords(substr($fileName, 20), '_'));
            $object = new ($this->namespace . '\\' . $className)();
            $this->log('Applying Migration ' . $toApplyMigration);
            $object->up();
            $this->log('Applied Migration ' . $toApplyMigration);
            $migrated[] = $toApplyMigration;
        }
        if (!empty($migrated)) {
            $subSQL = implode(',', array_map(fn($migration) => "('" . $migration . "')", $migrated));
            $stmt = $this->db->pdo->prepare(
                "INSERT INTO migrations (migration) VALUES 
            $subSQL
            "
            );
            $stmt->execute();
        } else {
            echo 'All migrations applied'.PHP_EOL;
        }
    }

    private function createMigrationsTable()
    {
        $this->db->pdo->exec(
            "CREATE TABLE IF NOT EXISTS migrations (
                    id int UNSIGNED AUTO_INCREMENT PRIMARY KEY ,
                    migration VARCHAR(255) NOT NULL ,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
            ) ENGINE = INNODB;"
        );
    }

    private function getAppliedMigrations()
    {
        $stmt = $this->db->pdo->prepare("SELECT migration FROM migrations");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function log(string $log)
    {
        echo '[' . date('Y-m-d H:i:s') . '] ' . $log . PHP_EOL;
    }
}