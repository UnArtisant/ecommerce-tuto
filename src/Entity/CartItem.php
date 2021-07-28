<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartItemRepository::class)
 * @ORM\Table(name="card_items")
 */
class CartItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Products $product;

    /**
     * @ORM\Column(type="integer")
     */
    private int $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="cartItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(?Products $product): self
    {
        $this->product = $product;

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
