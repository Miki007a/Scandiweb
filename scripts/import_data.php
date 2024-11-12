<?php

require_once __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../public/bootstrap.php';

use Scandiweb\factory\ProductFactory;
use Scandiweb\factory\AttributeFactory;
use Scandiweb\factory\CategoryFactory;
use Scandiweb\factory\ItemFactory;
use Scandiweb\factory\CurrencyFactory;
use Scandiweb\factory\ProductGalleryFactory;
use Scandiweb\factory\PriceFactory;
use Scandiweb\import\DataImporter;
use Scandiweb\factory\ProductAttributeValueFactory;

$importer = new DataImporter(
    entityManager: $app->getEntityManager(),
    productFactory: new ProductFactory(),
    categoryFactory: new CategoryFactory(),
    attributeFactory: new AttributeFactory(),
    itemFactory: new ItemFactory(),
    currencyFactory: new CurrencyFactory(),
    galleryFactory: new ProductGalleryFactory(),
    priceFactory: new PriceFactory(),
    productAttributeValueFactory: new ProductAttributeValueFactory()
);

$importer->import(); 