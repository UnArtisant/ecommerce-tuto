<?php


namespace App\Entity\shared;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait Address
{

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Address cannot be longer than {{ limit }} characters"
     * )
     */
    private string $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "City cannot be longer than {{ limit }} characters"
     * )
     */
    private string $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "State cannot be longer than {{ limit }} characters"
     * )
     */
    private string $state;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Country cannot be longer than {{ limit }} characters"
     * )
     */
    private string $country;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 45,
     *      maxMessage = "Zipcode cannot be longer than {{ limit }} characters"
     * )
     */
    private string $zipcode;

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


    public function getAddress(): string
    {
        return $this->address;
    }


    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

}