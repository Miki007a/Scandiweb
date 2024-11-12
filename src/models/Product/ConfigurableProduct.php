<?php

namespace Scandiweb\models\Product;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class ConfigurableProduct extends Product
{
    public function getAvailableOptions(): Collection
    {
        return $this->getAttributes();
    }
} 
        
