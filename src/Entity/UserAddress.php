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


}
