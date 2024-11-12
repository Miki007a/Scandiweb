<?php

namespace Scandiweb\config;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;

class DatabaseConfig implements ConfigInterface
{
    private array $dbParams;
    private array $paths;
    private bool $isDevMode;
    private static ?EntityManager $entityManager = null;

    public function __construct()
    {
        $this->loadEnvironmentVariables();
        $this->initializeConfiguration();
    }

    private function loadEnvironmentVariables(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
    }

    private function initializeConfiguration(): void
    {
        $this->paths = [__DIR__ . '/../../src/models'];
        $this->isDevMode = true;
        $this->dbParams = [
            'driver' => 'pdo_mysql',
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'dbname' => $_ENV['DB_NAME'],
            'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'port' => $_ENV['DB_PORT'] ?? 3306,
        ];
    }

    public function createEntityManager(): EntityManager
    {
        if (self::$entityManager === null) {
            $config = ORMSetup::createAttributeMetadataConfiguration($this->paths, $this->isDevMode);
            $connection = DriverManager::getConnection($this->dbParams, $config);
            self::$entityManager = new EntityManager($connection, $config);
        }
        
        return self::$entityManager;
    }

    public function testConnection(): bool
    {
        try {
            $em = $this->createEntityManager();
            $connection = $em->getConnection();
            
            $connection->executeQuery('SELECT 1');
            
            echo "Database connection successful!\n";
            echo "Connected to: " . $this->dbParams['dbname'] . " @ " . $this->dbParams['host'] . "\n";
            
            return true;
        } catch (\Exception $e) {
            echo "Connection failed!\n";
            echo "Error: " . $e->getMessage() . "\n";
            return false;
        }
    }
} 