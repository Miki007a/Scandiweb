<?php

namespace Scandiweb\factory;

use Scandiweb\models\Currency\Currency;

class CurrencyFactory
{
    public function create(array $data): Currency
    {
        return new Currency(
            label: $data['label'],
            symbol: $data['symbol']
        );
    }
} 