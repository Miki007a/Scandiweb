<?php

use Scandiweb\config\DatabaseConfig;
use Scandiweb\core\Application;
use Scandiweb\router\RouterConfig;

require_once __DIR__ . '/../vendor/autoload.php';


$app = new Application(
    databaseConfig: new DatabaseConfig(),
    routerConfig: new RouterConfig(),
);

return $app->initialize();

