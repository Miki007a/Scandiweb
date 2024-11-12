<?php

namespace Scandiweb\models\Attribute;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'items')]
class Item
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    protected string $id;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $displayValue;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $value;

    public function __construct(string $displayValue, string $value, string $id) {
        $this->displayValue = $displayValue;
        $this->value = $value;
        $this->id = $id;
    }

    public function getDisplayValue(): string {
        return $this->displayValue;
    }

    public function getValue(): string {
        return $this->value;
    }

    public function getId(): string {
        return $this->id;
    }
}
