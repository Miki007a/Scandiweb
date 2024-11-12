<?php

namespace Scandiweb\models\Currency;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'currencies')]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'string', length: 10)]
    protected string $label;

    #[ORM\Column(type: 'string', length: 5)]
    protected string $symbol;

    public function __construct(string $label, string $symbol)
    {
        $this->label = $label;
        $this->symbol = $symbol;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }
}
