<?php

namespace App\Entity;

use App\Entity\shared\Timestamp;
use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderItemRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class OrderItem
{

    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $productName;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class, inversedBy="orderItems")
     */
    private Products $product;

    /**
     * @ORM\Column(type="integer")
     */
    private int $unit_price;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="orderItems")
     */
    private Order $orderTransaction;

    /**
     * @ORM\Column(type="integer")
     */
    private int $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(Products $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getUnitPrice(): ?int
    {
        return $this->unit_price;
    }

    public function setUnitPrice(int $unit_price): self
    {
        $this->unit_price = $unit_price;

        return $this;
    }

    public function getOrderTransaction(): ?Order
    {
        return $this->orderTransaction;
    }

    public function setOrderTransaction(Order $orderTransaction): self
    {
        $this->orderTransaction = $orderTransaction;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
