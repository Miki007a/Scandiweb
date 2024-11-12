<?php
namespace Scandiweb\graphql\resolvers;

use Scandiweb\models\Category\Category;
use Doctrine\ORM\EntityManager;

class CategoryResolver
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getCategories(): array
    {
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        return $categoryRepository->findAll();

    }

    public function getCategoryByName(string $name): Category
    {
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        return $categoryRepository->findOneBy(['name' => $name]);
    }

}
