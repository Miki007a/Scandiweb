<?php

namespace Scandiweb\factory;

use Scandiweb\models\ProductGallery\ProductGallery;
use Scandiweb\models\Product\Product;

class ProductGalleryFactory
{
    public function create(string $imageUrl, ?Product $product = null): ProductGallery
    {
        return new ProductGallery($imageUrl, $product);
    }
} 