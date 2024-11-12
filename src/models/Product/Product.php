<?php

namespace Scandiweb\models\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Scandiweb\models\Attribute\Attribute;
use Scandiweb\models\Category\Category;
use Scandiweb\models\Order\OrderItem;
use Scandiweb\models\Price\Price;
use Scandiweb\models\ProductGallery\ProductGallery;
use Scandiweb\models\ProductAttributeValue\ProductAttributeValue;

#[ORM\Entity]
#[ORM\Table(name: 'products')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'product_type', type: 'string')]
#[ORM\DiscriminatorMap([
    'configurable' => ConfigurableProduct::class,
    'simple' => SimpleProduct::class
])]
abstract class Product
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    protected string $id;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $name;

    #[ORM\Column(type: 'boolean')]
    protected bool $inStock;

    #[ORM\Column(type: 'text')]
    protected string $description;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $brand;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    protected ?Category $category;

    #[ORM\OneToMany(targetEntity: ProductGallery::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
    protected Collection $gallery;

    #[ORM\OneToMany(targetEntity: Price::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
    protected Collection $prices;

    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'product')]
    protected Collection $orderItems;

    #[ORM\OneToMany(targetEntity: ProductAttributeValue::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
    protected Collection $attributeValues;

    public function __construct(
        string $id,
        string $name,
        bool $inStock,
        string $description,
        string $brand,
        Category $category
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->inStock = $inStock;
        $this->description = $description;
        $this->brand = $brand;
        $this->category = $category;
        $this->gallery = new ArrayCollection();
        $this->prices = new ArrayCollection();
        $this->orderItems = new ArrayCollection();
        $this->attributeValues = new ArrayCollection();
    }

    public function getId(): string {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function isInStock(): bool {
        return $this->inStock;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getBrand(): string {
        return $this->brand;
    }

    public function getCategory(): ?Category {
        return $this->category;
    }

    /**
     * @return Collection|ProductGallery[]
     */
    public function getGallery(): Collection
    {
        return $this->gallery;
    }


    /** @return Collection<int, Price> */

    public function getPrices(): Collection {
        return $this->prices;
    }

    abstract public function getAvailableOptions(): Collection;

    public function addAttributeValue(ProductAttributeValue $attributeValue): void
    {
        if (!$this->attributeValues->contains($attributeValue)) {
            $this->attributeValues->add($attributeValue);
        }
    }

    public function getAttributeValues(): Collection
    {
        return $this->attributeValues;
    }

    /**
     * @return Collection|Attribute[]
     */
    public function getAttributes(): Collection
    {
        $attributes = new ArrayCollection();
        foreach ($this->attributeValues as $attributeValue) {
            $attributes->add($attributeValue->getAttribute());
        }
        return $attributes;
    }
}
