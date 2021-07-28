<?php

namespace App\Entity;

use App\Repository\OrderAddressRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderAddressRepository::class)
 * @ORM\Table(name="order_addresses")
 */
class OrderAddress
{
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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $zipcode;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }
}
