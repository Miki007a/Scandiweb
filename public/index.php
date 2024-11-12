<?php
use Scandiweb\graphql\resolvers\ProductResolver;
use Scandiweb\graphql\resolvers\CategoryResolver;
use Scandiweb\graphql\resolvers\OrderResolver;

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap.php';

$entityManager = $app->getEntityManager();
$productResolver = new ProductResolver($entityManager);
$categoryResolver = new CategoryResolver($entityManager);
$orderResolver = new OrderResolver($entityManager, $productResolver);

$router = new Scandiweb\Router\RouterConfig();
$router->setResolvers($productResolver, $categoryResolver, $orderResolver);
$router->configure();
$router->dispatch();
