<?php

namespace App\Entity;

use App\Entity\shared\Address;
use App\Entity\shared\Timestamp;
use App\Repository\OrderAddressRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderAddressRepository::class)
 * @ORM\Table(name="order_addresses")
 * @ORM\HasLifecycleCallbacks()
 */
class OrderAddress
{

    use Timestamp;
    use Address;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int  $id;

    /**
     * @ORM\OneToOne(targetEntity=Order::class, inversedBy="orderAddress", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private Order $owner;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): Order
    {
        return $this->owner;
    }

    public function setOwner(Order $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

}
