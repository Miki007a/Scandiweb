<?php

namespace Scandiweb\factory;

use Scandiweb\models\Attribute\Item;

class ItemFactory
{
    public function create(array $data): Item
    {
        return new Item(
            displayValue: $data['displayValue'],
            value: $data['value'],
            id: $data['id']
        );
    }
} 