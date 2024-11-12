<?php

namespace Scandiweb\config;

use Doctrine\ORM\EntityManager;

interface ConfigInterface
{
    public function createEntityManager(): EntityManager;
} 