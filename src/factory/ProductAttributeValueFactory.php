<?php

namespace Scandiweb\factory;

use Scandiweb\models\Product\Product;
use Scandiweb\models\Attribute\Attribute;
use Scandiweb\models\ProductAttributeValue\ProductAttributeValue;

class ProductAttributeValueFactory
{
    public function create(Product $product, Attribute $attribute): ProductAttributeValue
    {
        return new ProductAttributeValue($product, $attribute);
    }
} 