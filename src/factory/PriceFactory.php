<?php

namespace Scandiweb\factory;

use Scandiweb\models\Price\Price;
use Scandiweb\models\Currency\Currency;
use Scandiweb\models\Product\Product;

class PriceFactory
{
    public function create(float $amount, Currency $currency, Product $product): Price
    {
        return new Price($amount, $currency, $product);
    }
} 