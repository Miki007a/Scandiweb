<?php

namespace Scandiweb\models\Price;

use Doctrine\ORM\Mapping as ORM;
use Scandiweb\models\Currency\Currency;
use Scandiweb\models\Product\Product;

#[ORM\Entity]
#[ORM\Table(name: 'prices')]
class Price
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    protected float $amount;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: 'currency_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected Currency $currency;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'prices')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    protected Product $product;

    public function __construct(float $amount, Currency $currency, Product $product)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->product = $product;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }
    public function getProduct(): Product {
        return $this->product;
    }

}
