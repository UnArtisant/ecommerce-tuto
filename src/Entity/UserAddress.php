<?php

namespace App\Entity;

use App\Entity\shared\Address;
use App\Entity\shared\Timestamp;
use App\Repository\UserAddressRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserAddressRepository::class)
 * @ORM\Table(name="user_addresses")
 * @ORM\HasLifecycleCallbacks()
 */
class UserAddress
{

    use Timestamp;
    use Address;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userAddresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $owner;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDefaultAddress;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setDefaultAddress () {
        if($this->isDefaultAddress === null) {
            $this->isDefaultAddress = false;
        }
    }


    public function __toString() : string
    {
        return $this->address . " " . $this->getCity();
    }

    public function getIsDefaultAddress(): ?bool
    {
        return $this->isDefaultAddress;
    }

    public function setIsDefaultAddress(?bool $isDefaultAddress): self
    {
        $this->isDefaultAddress = $isDefaultAddress;

        return $this;
    }
}
