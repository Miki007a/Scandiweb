<?php

namespace Scandiweb\models\ProductGallery;

use Doctrine\ORM\Mapping as ORM;
use Scandiweb\models\Product\Product;

#[ORM\Entity]
#[ORM\Table(name: 'product_gallery')]
class ProductGallery
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $imageUrl;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'gallery')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected ?Product $product;

    public function __construct(string $imageUrl, ?Product $product = null)
    {
        $this->imageUrl = $imageUrl;
        $this->product = $product;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }
}
