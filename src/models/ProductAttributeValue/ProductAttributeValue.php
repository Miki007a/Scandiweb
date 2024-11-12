<?php

namespace Scandiweb\models\ProductAttributeValue;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Scandiweb\models\Product\Product;
use Scandiweb\models\Attribute\Attribute;
use Scandiweb\models\Attribute\Item;

#[ORM\Entity]
#[ORM\Table(name: 'product_attribute_values')]
class ProductAttributeValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'attributeValues')]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: Attribute::class)]
    private Attribute $attribute;

    #[ORM\ManyToMany(targetEntity: Item::class)]
    #[ORM\JoinTable(name: 'product_attribute_value_items')]
    private Collection $items;

    public function __construct(Product $product, Attribute $attribute)
    {
        $this->product = $product;
        $this->attribute = $attribute;
        $this->items = new ArrayCollection();
    }

    public function addItem(Item $item): void
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
        }
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }
} 