<?php

namespace Scandiweb\models\Product;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
class SimpleProduct extends Product
{
    public function getAvailableOptions(): Collection
    {
        return new ArrayCollection(); 
    }
} 