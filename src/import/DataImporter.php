<?php
namespace Scandiweb\import;
use Scandiweb\factory\ProductFactory;
use Scandiweb\factory\AttributeFactory;
use Scandiweb\factory\CategoryFactory;
use Scandiweb\factory\ItemFactory;
use Scandiweb\factory\CurrencyFactory;
use Scandiweb\factory\ProductGalleryFactory;
use Scandiweb\factory\PriceFactory;
use Scandiweb\models\Category\Category;
use Scandiweb\models\Currency\Currency;
use Scandiweb\models\Attribute\Item;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Scandiweb\models\Attribute\TextAttribute;
use Scandiweb\models\Attribute\SwatchAttribute;
use Scandiweb\models\Attribute\Attribute;
use Scandiweb\factory\ProductAttributeValueFactory;

class DataImporter
{
    private array $data;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProductFactory $productFactory,
        private readonly CategoryFactory $categoryFactory,
        private readonly AttributeFactory $attributeFactory,
        private readonly ItemFactory $itemFactory,
        private readonly CurrencyFactory $currencyFactory,
        private readonly ProductGalleryFactory $galleryFactory,
        private readonly PriceFactory $priceFactory,
        private readonly ProductAttributeValueFactory $productAttributeValueFactory
    ) {
        $this->data = $this->loadJsonData();
    }

    public function import(): void
    {
        try {
            $this->importCategories();
            $this->importProducts();
            
            $this->entityManager->flush();
            echo "Data imported successfully.";
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
            echo "\nStack trace: " . $e->getTraceAsString();
        }
    }

    private function importCategories(): void
    {
        foreach ($this->data['data']['categories'] as $categoryData) {
            $category = $this->categoryFactory->create($categoryData);
            $this->entityManager->persist($category);
        }
        $this->entityManager->flush();
    }

    private function importProducts(): void
    {
        foreach ($this->data['data']['products'] as $productData) {
            $category = $this->entityManager->getRepository(Category::class)
                ->findOneBy(['name' => $productData['category']]);

            $product = $this->productFactory->create($productData, $category);
            
            $this->handleProductGallery($product, $productData['gallery']);
            $this->handleProductPrices($product, $productData['prices']);
            $this->handleProductAttributes($product, $productData['attributes'] ?? []);

            $this->entityManager->persist($product);
        }
    }

    private function handleProductGallery($product, array $galleryUrls): void
    {
        foreach ($galleryUrls as $imageUrl) {
            $gallery = $this->galleryFactory->create($imageUrl, $product);
            $this->entityManager->persist($gallery);
            $product->getGallery()->add($gallery);
        }
    }

    private function handleProductPrices($product, array $pricesData): void
    {
        foreach ($pricesData as $priceData) {
            $currency = $this->getOrCreateCurrency($priceData['currency']);
            $price = $this->priceFactory->create(
                amount: $priceData['amount'],
                currency: $currency,
                product: $product
            );
            $this->entityManager->persist($price);
            $product->getPrices()->add($price);
        }
    }

    private function getOrCreateCurrency(array $currencyData): Currency
    {
        $currency = $this->entityManager->getRepository(Currency::class)
            ->findOneBy(['label' => $currencyData['label']]);

        if (!$currency) {
            $currency = $this->currencyFactory->create($currencyData);
            $this->entityManager->persist($currency);
        }

        return $currency;
    }

    private function handleProductAttributes($product, array $attributesData): void
    {
        foreach ($attributesData as $attributeData) {
            $attribute = $this->getOrCreateAttribute($attributeData);
            
            if ($attribute) {
                $productAttribute = $this->productAttributeValueFactory->create($product, $attribute);
                $this->entityManager->persist($productAttribute);
                
                $product->addAttributeValue($productAttribute);

                foreach ($attributeData['items'] as $itemData) {
                    $item = $this->getOrCreateItem($itemData);
                    $productAttribute->addItem($item);
                }
            }
        }
    }

    private function getOrCreateAttribute(array $attributeData): ?Attribute
    {
        $repository = $attributeData['type'] === 'text' 
            ? $this->entityManager->getRepository(TextAttribute::class)
            : $this->entityManager->getRepository(SwatchAttribute::class);

        $attribute = $repository->find($attributeData['id']);

        if (!$attribute) {
            $attribute = $this->attributeFactory->create($attributeData);
            $this->entityManager->persist($attribute);
        }

        return $attribute;
    }

    private function getOrCreateItem(array $itemData): Item
    {
        $item = $this->entityManager->getRepository(Item::class)
            ->find($itemData['id']);

        if (!$item) {
            $item = $this->itemFactory->create($itemData);
            $this->entityManager->persist($item);
        }

        return $item;
    }

    private function loadJsonData(): array
    {
        $json = file_get_contents(__DIR__ . '/../data/data.json');
        return json_decode($json, true);
    }
}


