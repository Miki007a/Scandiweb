<?php
namespace Scandiweb\graphql\types;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class Types
{
    private static array $types = [];

    public static function product(): callable
    {
        return static fn() => self::$types['Product'] ??= new ObjectType([
            'name' => 'Product',
            'fields' => [
                'id' => [
                    'type' => Type::string(),
                    'resolve' => function ($product) {
                        return $product->getId();
                    },
                ],
                'name' => [
                    'type' => Type::string(),
                    'resolve' => function ($product) {
                        return $product->getName();
                    },
                ],
                'inStock' => [
                    'type' => Type::boolean(),
                    'resolve' => function ($product) {
                        return $product->isInStock();
                    },
                ],
                'description' => [
                    'type' => Type::string(),
                    'resolve' => function ($product) {
                        return $product->getDescription();
                    },
                ],
                'brand' => [
                    'type' => Type::string(),
                    'resolve' => function ($product) {
                        return $product->getBrand();
                    },
                ],
                'gallery' => [
                    'type' => Type::listOf(self::productGallery()),
                    'resolve' => function ($product) {
                        return $product->getGallery()->toArray();
                    },
                ],
                'prices' => [
                    'type' => Type::listOf(self::price()),
                    'resolve' => function ($product) {
                        return $product->getPrices()->toArray();
                    },
                ],
                'attributes' => [
                    'type' => Type::listOf(self::attributeSet()),
                    'resolve' => function ($product) {
                        return $product->getAttributeValues()->map(function($pav) {
                            return [
                                'name' => $pav->getAttribute()->getName(),
                                'type' => $pav->getAttribute()->getType(),
                                'items' => $pav->getItems()->map(function($item) {
                                    return [
                                        'displayValue' => $item->getDisplayValue(),
                                        'value' => $item->getValue()
                                    ];
                                })->toArray()
                            ];
                        })->toArray();
                    },
                ],
                'category' => [
                    'type' => self::category(),
                    'resolve' => function ($product) {
                        return $product->getCategory();
                    },
                ],
            ],
        ]);
    }

    public static function price(): callable
    {
        return static fn() => self::$types['Price'] ??= new ObjectType([
            'name' => 'Price',
            'fields' => [
                'amount' => [
                    'type' => Type::float(),
                    'resolve' => function ($price) {
                        return $price->getAmount();
                    },
                ],
                'currency' => [
                    'type' => self::currency(),
                    'resolve' => function ($price) {
                        return $price->getCurrency();
                    },
                ],
            ],
        ]);
    }

    public static function currency(): callable
    {
        return static fn() => self::$types['Currency'] ??= new ObjectType([
            'name' => 'Currency',
            'fields' => [
                'label' => [
                    'type' => Type::string(),
                    'resolve' => function ($currency) {
                        return $currency->getLabel();
                    },
                ],
                'symbol' => [
                    'type' => Type::string(),
                    'resolve' => function ($currency) {
                        return $currency->getSymbol();
                    },
                ],
            ],
        ]);
    }

    public static function category(): callable
    {
        return static fn() => self::$types['Category'] ??= new ObjectType([
            'name' => 'Category',
            'fields' => [
                'id' => [
                    'type' => Type::int(),
                    'resolve' => function ($category) {
                        return $category->getId();
                    },
                ],
                'name' => [
                    'type' => Type::string(),
                    'resolve' => function ($category) {
                        return $category->getName();
                    },
                ],
            ],
        ]);
    }

    public static function productGallery(): callable
    {
        return static fn() => self::$types['ProductGallery'] ??= new ObjectType([
            'name' => 'ProductGallery',
            'fields' => [
                'imageUrl' => [
                    'type' => Type::string(),
                    'resolve' => function ($gallery) {
                        return $gallery->getImageUrl();
                    },
                ],
            ],
        ]);
    }
    public static function attribute(): callable
    {
        return static fn() => self::$types['Attribute'] ??= new ObjectType([
            'name' => 'Attribute',
            'fields' => [
                'id' => [
                    'type' => Type::string(),
                    'resolve' => function ($attribute) {
                        return $attribute->getId();
                    }
                ],
                'name' => [
                    'type' => Type::string(),
                    'resolve' => function ($attribute) {
                        return $attribute->getName();
                    }
                ],
                'type' => [
                    'type' => Type::string(),
                    'resolve' => function ($attribute) {
                        return $attribute->getType();
                    }
                ]
               
            ],
        ]);
    }

    // Item Type
    public static function item(): callable
    {
        return static fn() => self::$types['Item'] ??= new ObjectType([
            'name' => 'Item',
            'fields' => [
                'id' => [
                    'type' => Type::string(),
                     'resolve' => function ($item) {
                     return $item->getId();
                     }
                ],
                'displayValue' => [
                    'type' => Type::string(),
                    'resolve' => function ($item) {
             return $item->getDisplayValue();
                    }
                ],
                'value' => [
                    'type' => Type::string(),
                    'resolve' => function ($item) {
             return $item->getValue();
                    }
                ],
            ],
        ]);
    }
    // OrderItem Type
    public static function orderItem(): callable
    {
        return static fn() => self::$types['OrderItem'] ??= new ObjectType([
            'name' => 'OrderItem',
            'fields' => [
                'id' => [
                    'type' => Type::int(),
                    'resolve' => function ($orderItem) {
                        return $orderItem->getId();
                    },
                ],
                'product' => [
                    'type' => self::product(),
                    'resolve' => function ($orderItem) {
                        return $orderItem->getProduct();
                    },
                ],
                'quantity' => [
                    'type' => Type::int(),
                    'resolve' => function ($orderItem) {
                        return $orderItem->getQuantity();
                    },
                ],
            ],
        ]);
    }

// Order Type
    public static function order(): callable
    {
        return static fn() => self::$types['Order'] ??= new ObjectType([
            'name' => 'Order',
            'fields' => [
                'id' => [
                    'type' => Type::int(),
                    'resolve' => function ($order) {
                        return $order->getId();
                    },
                ],
                'total' => [
                    'type' => Type::float(),
                    'resolve' => function ($order) {
                        return $order->getTotal();
                    },
                ],
                'orderItems' => [
                    'type' => Type::listOf(self::orderItem()),
                    'resolve' => function ($order) {
                        return $order->getOrderItems();
                    },
                ],
            ],
        ]);
    }
    public static function orderItemInput(): InputObjectType
    {
        return self::$types['OrderItemInput'] ??= new InputObjectType([
            'name' => 'OrderItemInput',
            'fields' => [
                'productId' => ['type' => Type::string()],
                'quantity' => ['type' => Type::int()],
            ],
        ]);
    }

    public static function productAttributeValue(): callable
    {
        return static fn() => self::$types['ProductAttributeValue'] ??= new ObjectType([
            'name' => 'ProductAttributeValue',
            'fields' => [
                'id' => [
                    'type' => Type::int(),
                    'resolve' => fn($pav) => $pav->getId()
                ],
                'attribute' => [
                    'type' => self::attribute(),
                    'resolve' => fn($pav) => $pav->getAttribute()
                ],
                'items' => [
                    'type' => Type::listOf(self::item()),
                    'resolve' => fn($pav) => $pav->getItems()->toArray()
                ]
            ]
        ]);
    }

    public static function attributeSet(): callable
    {
        return static fn() => self::$types['AttributeSet'] ??= new ObjectType([
            'name' => 'AttributeSet',
            'fields' => [
                'name' => [
                    'type' => Type::string(),
                    'resolve' => fn($set) => $set['name']
                ],
                'type' => [
                    'type' => Type::string(),
                    'resolve' => fn($set) => $set['type']
                ],
                'items' => [
                    'type' => Type::listOf(self::attributeItem()),
                    'resolve' => fn($set) => $set['items']
                ]
            ]
        ]);
    }

    public static function attributeItem(): callable
    {
        return static fn() => self::$types['AttributeItem'] ??= new ObjectType([
            'name' => 'AttributeItem',
            'fields' => [
                'displayValue' => [
                    'type' => Type::string(),
                    'resolve' => fn($item) => $item['displayValue']
                ],
                'value' => [
                    'type' => Type::string(),
                    'resolve' => fn($item) => $item['value']
                ]
            ]
        ]);
    }

}
