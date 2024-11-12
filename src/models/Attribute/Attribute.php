<?php

namespace Scandiweb\models\Attribute;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'attributes')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discriminator', type: 'string')]
#[ORM\DiscriminatorMap(['text' => 'TextAttribute', 'swatch' => 'SwatchAttribute'])]
abstract class Attribute
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    protected string $id;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $name;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $type;

    public function __construct(string $id, string $name, string $type) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getType(): string {
        return $this->type;
    }

}
