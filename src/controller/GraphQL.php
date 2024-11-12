<?php

namespace Scandiweb\controller;

use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use RuntimeException;
use Scandiweb\graphql\resolvers\CategoryResolver;
use Scandiweb\graphql\resolvers\OrderResolver;
use Scandiweb\graphql\types\Types;
use Scandiweb\graphql\resolvers\ProductResolver;
use Throwable;


class GraphQL {

    private ProductResolver $productResolver;
    private CategoryResolver $categoryResolver;
    private OrderResolver $orderResolver;



    public function __construct(ProductResolver $productResolver,CategoryResolver $categoryResolver,OrderResolver $orderResolver)
    {
        $this->productResolver = $productResolver;
        $this->categoryResolver = $categoryResolver;
        $this->orderResolver = $orderResolver;
    }

    public function handle() {

        try {
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'getProducts' => [
                        'type' => Type::listOf(Types::product()),
                        'resolve' => function () {
                            return $this->productResolver->getProducts();
                        },
                    ],
                    'getProductsByCategory' => [
                        'type' => Type::listOf(Types::product()),
                        'args' => [
                            'categoryName' => Type::nonNull(Type::string()),
                        ],
                        'resolve' => function ($root, $args) {
                            $category = $this->categoryResolver->getCategoryByName($args['categoryName']);
                            return $this->productResolver->getProductsByCategory($category);
                        },
                    ],
                    'getCategories' => [
                        'type' => Type::listOf(Types::category()),
                        'resolve' => function () {
                       return $this->categoryResolver->getCategories();
                        }
                    ],
                    'getProductById' => [
                'type' => Types::product(), 
                'args' => [
                    'id' => Type::nonNull(Type::string()),
                ],
                'resolve' => function ($root, $args) {
                    return $this->productResolver->getProductById($args['id']);
                },
            ],
                ],
            ]);


            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    'sum' => [
                        'type' => Type::int(),
                        'args' => [
                            'x' => ['type' => Type::int()],
                            'y' => ['type' => Type::int()],
                        ],
                        'resolve' => static fn($calc, array $args): int => $args['x'] + $args['y'],
                    ],
                    'placeOrder' => [
                        'type' => Types::order(),
                        'args' => [
                            'orderItems' => [
                                'type' => Type::listOf(types::orderItemInput()),
                            ],
                        ],
                        'resolve' => function ($root, array $args)  {
                            return $this->orderResolver->createOrder($args['orderItems']);
                        },
                    ],
                ],
            ]);


            $schema = new Schema(
                (new SchemaConfig())
                    ->setQuery($queryType)
                    ->setMutation($mutationType)
            );

            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
            }

            $input = json_decode($rawInput, true);
            $query = $input['query'];
            $variableValues = $input['variables'] ?? null;

            $rootValue = ['prefix' => 'You said: '];
            $result = GraphQLBase::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output = $result->toArray();
        } catch (Throwable $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        return json_encode($output);
    }


}