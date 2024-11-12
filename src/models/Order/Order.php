<?php

namespace Scandiweb\models\Order;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Scandiweb\models\Order\OrderItem;

#[ORM\Entity]
#[ORM\Table(name: 'orders')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'float')]
    protected float $total;

    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', cascade: ['persist', 'remove'])]
    protected Collection $orderItems;

    public function __construct(float $total = 0)
    {
        $this->total = $total;
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): void
    {
        $this->total = $total;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): void
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setOrder($this);
        }
    }

    public function removeOrderItem(OrderItem $orderItem): void
    {
        if ($this->orderItems->contains($orderItem)) {
            $this->orderItems->removeElement($orderItem);
            if ($orderItem->getOrder() === $this) {
                $orderItem->setOrder(null);
            }
        }
    }
}
