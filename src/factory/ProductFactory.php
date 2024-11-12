<?php

namespace Scandiweb\factory;

use Scandiweb\models\Product\Product;
use Scandiweb\models\Product\SimpleProduct;
use Scandiweb\models\Product\ConfigurableProduct;
use Scandiweb\models\Category\Category;

class ProductFactory
{
    public function create(array $data, Category $category): Product
    {
        $isConfigurable = !empty($data['attributes']);
        
        $productClass = $isConfigurable ? ConfigurableProduct::class : SimpleProduct::class;
        
        return new $productClass(
            $data['id'],
            $data['name'],
            $data['inStock'],
            $data['description'],
            $data['brand'],
            $category
        );
    }
} 