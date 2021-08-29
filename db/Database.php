<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package app\core
 */

namespace app\core\db;

use app\core\Application;
use PDO;

/**
 * @property PDO pdo
 */

class Database
{
    public function __construct (array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations ()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];

        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration)
        {
            if ($migration === '.' || $migration === '..')
                continue;

            require_once Application::$ROOT_DIR.'/migrations/'.$migration;

            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();

            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Migration $migration applied");

            $newMigrations[] = $migration;
        }

        if ( !empty($newMigrations) )
            $this->saveMigrations($newMigrations);
        else
            $this->log('All migrations have been applied');
    }

    public function createMigrationsTable ()
    {
        $this->pdo->exec
        ( "CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ) ENGINE = INNODB; ");
    }

    private function getAppliedMigrations ()
    {
        $stmt = $this->pdo->prepare("SELECT migration FROM migrations");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function saveMigrations(array $migrations)
    {
        $str = implode( ",", array_map( function ($m) { return "('$m')"; }, $migrations) );
        $stmt = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        $stmt->execute();
    }

    public function prepare ($sql)
    {
        return $this->pdo->prepare($sql);
    }

    protected function log ($message)
    {
        echo '[' . date('Y-m-d h:i:s') . '] - ' . $message . PHP_EOL;
    }
}