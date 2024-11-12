<?php

namespace Scandiweb\router;

use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use Scandiweb\graphql\resolvers\ProductResolver;
use Scandiweb\graphql\resolvers\CategoryResolver;
use Scandiweb\graphql\resolvers\OrderResolver;
use Scandiweb\controller\GraphQL;
use Scandiweb\config\AppConfig;

class RouterConfig
{
    private Dispatcher $dispatcher;
    private ProductResolver $productResolver;
    private CategoryResolver $categoryResolver;
    private OrderResolver $orderResolver;
    private AppConfig $config;

    public function __construct()
    {
        $this->config = AppConfig::getInstance();
    }

    public function configure(): void
    {
        $this->dispatcher = \FastRoute\simpleDispatcher(function(RouteCollector $r) {
            $r->addRoute(['POST', 'OPTIONS'], '/graphql', [GraphQL::class, 'handle']);
        });
    }

    public function dispatch(): void
    {
        $corsConfig = $this->config->get('cors');
        
        header('Access-Control-Allow-Origin: ' . $corsConfig['allowed_origins']);
        header('Access-Control-Allow-Methods: ' . $corsConfig['allowed_methods']);
        header('Access-Control-Allow-Headers: ' . $corsConfig['allowed_headers']);

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            exit();
        }
        
        $routeInfo = $this->dispatcher->dispatch(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI']
        );

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                header('HTTP/1.0 404 Not Found');
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                header('HTTP/1.0 405 Method Not Allowed');
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                if (is_array($handler)) {
                    [$class, $method] = $handler;
                    $controller = $this->resolveController($class);
                    $response = $controller->$method($vars);
                    $this->sendResponse($response);
                }
                break;
        }
    }

    public function setResolvers(
        ProductResolver $productResolver,
        CategoryResolver $categoryResolver,
        OrderResolver $orderResolver
    ): void {
        $this->productResolver = $productResolver;
        $this->categoryResolver = $categoryResolver;
        $this->orderResolver = $orderResolver;
    }

    private function resolveController(string $class): object
    {
        if ($class === GraphQL::class) {
            return new $class(
                $this->productResolver,
                $this->categoryResolver,
                $this->orderResolver
            );
        }
        
        return new $class();
    }

    private function sendResponse($response): void
    {
        echo $response;
    }
} 