<?php

namespace Scandiweb\config;

class AppConfig
{
    private static ?self $instance = null;
    private array $config;

    private function __construct()
    {
        $this->loadEnvironmentVariables();
        $this->initializeConfig();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadEnvironmentVariables(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
    }

    private function initializeConfig(): void
    {
        $this->config = [
            'app' => [
                'name' => $_ENV['APP_NAME'] ?? 'Scandiweb App',
                'env' => $_ENV['APP_ENV'] ?? 'development',
                'debug' => (bool)($_ENV['APP_DEBUG'] ?? true),
            ],
            'cors' => [
                'allowed_origins' => $_ENV['CORS_ALLOWED_ORIGINS'] ?? '*',
                'allowed_methods' => 'GET, POST, OPTIONS',
                'allowed_headers' => 'Content-Type, Authorization',
            ],
         
        ];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }
} 