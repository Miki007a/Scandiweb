<?php

namespace Scandiweb\graphql\resolvers;

use Doctrine\ORM\EntityManager;
use Scandiweb\models\Order\Order;
use Scandiweb\models\Order\OrderItem;


class OrderResolver
{
    private EntityManager $entityManager;

    private ProductResolver $productResolver;

    public function __construct(EntityManager $entityManager, productResolver $productResolver)
    {
        $this->entityManager = $entityManager;
        $this->productResolver = $productResolver;
    }

    public function createOrder(array $orderItemsData): Order
    {
        $order = new Order();
        error_log("Starting order creation process...");

        try {
            $this->entityManager->beginTransaction();
            $total = 0.0;

            foreach ($orderItemsData as $itemData) {


                $product = $this->productResolver->getProductById($itemData['productId']);


                $orderItem = new OrderItem();
                $orderItem->setProduct($product);
                $orderItem->setQuantity($itemData['quantity']);
                $price = $product->getPrices()->first();


                $itemTotal = $price->getAmount() * $itemData['quantity'];

                $total += $itemTotal;
                $order->addOrderItem($orderItem);
                $this->entityManager->persist($orderItem);
            }


            $order->setTotal($total);

            $this->entityManager->persist($order);

            $this->entityManager->flush();
            $this->entityManager->commit();

            return $order;

        } catch (\Exception $e) {

            $this->entityManager->rollback();
            error_log("Error in createOrder: " . $e->getMessage());
            throw $e;
        }
    }

}