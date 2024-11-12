<?php

namespace Scandiweb\factory;

use Scandiweb\models\Attribute\Attribute;
use Scandiweb\models\Attribute\TextAttribute;
use Scandiweb\models\Attribute\SwatchAttribute;

class AttributeFactory
{
    public function create(array $data): Attribute
    {
        return match ($data['type']) {
            'text' => new TextAttribute($data['id'], $data['name']),
            'swatch' => new SwatchAttribute($data['id'], $data['name']),
            default => throw new \InvalidArgumentException("Unknown attribute type: {$data['type']}")
        };
    }
} 