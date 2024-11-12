<?php
namespace Scandiweb\graphql\resolvers;

use Scandiweb\models\Category\Category;
use Scandiweb\models\Product\Product;
use Doctrine\ORM\EntityManager;

class ProductResolver
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getProducts(): array
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        $products = $productRepository->findAll();
        return $products;
    }

    public function getProductsByCategory(Category $category): array
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        return $productRepository->findBy(['category' => $category]);
    }
    public function getProductById(string $id): Product{
        $productRepository = $this->entityManager->getRepository(Product::class);
        return $productRepository->find($id);
    }

}
