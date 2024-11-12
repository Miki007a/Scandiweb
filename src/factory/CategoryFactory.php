<?php

namespace Scandiweb\factory;

use Scandiweb\models\Category\Category;

class CategoryFactory
{
    public function create(array $data): Category
    {
        return new Category($data['name']);
    }
} 