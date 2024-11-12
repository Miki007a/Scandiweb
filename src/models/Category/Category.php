<?php

namespace Scandiweb\models\Category;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Scandiweb\models\Product\Product;

#[ORM\Entity]
#[ORM\Table(name: 'categories')]
class Category
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $name;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'category')]
    protected Collection  $products;

    public function __construct(string $name) {
        $this->name = $name;
        $this->products = new ArrayCollection();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

}
