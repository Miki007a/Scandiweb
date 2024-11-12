<?php

namespace Scandiweb\core;

use Scandiweb\config\DatabaseConfig;
use Scandiweb\router\RouterConfig;
use Scandiweb\config\AppConfig;
use Doctrine\ORM\EntityManager;
use Scandiweb\graphql\resolvers\ProductResolver;
use Scandiweb\graphql\resolvers\CategoryResolver;
use Scandiweb\graphql\resolvers\OrderResolver;

class Application
{
    private EntityManager $entityManager;
    private AppConfig $appConfig;
    
    public function __construct(
        private readonly DatabaseConfig $databaseConfig,
        private readonly RouterConfig $routerConfig
    ) {
        $this->appConfig = AppConfig::getInstance();
    }

    public function initialize(): self
    {
        $this->entityManager = $this->databaseConfig->createEntityManager();
 
        $productResolver = new ProductResolver($this->entityManager);
        $categoryResolver = new CategoryResolver($this->entityManager);
        $orderResolver = new OrderResolver($this->entityManager, $productResolver);
        
        $this->routerConfig->setResolvers($productResolver, $categoryResolver, $orderResolver);
        $this->routerConfig->configure();
        
        return $this;
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    public function getConfig(): AppConfig
    {
        return $this->appConfig;
    }

    public function router(): RouterConfig
    {
        return $this->routerConfig;
    }
} 