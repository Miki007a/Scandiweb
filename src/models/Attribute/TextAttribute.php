<?php

namespace Scandiweb\models\Attribute;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class TextAttribute extends Attribute
{
    public function __construct(string $id, string $name) {
        parent::__construct($id, $name, 'text');
    }

 
}
