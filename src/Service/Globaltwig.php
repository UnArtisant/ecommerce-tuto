<?php


namespace App\Service;


use App\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class Globaltwig
{

    private EntityManagerInterface $em;

    private CartItemRepository $cartItemRepository;

    private Security $security;

    /**
     * Globaltwig constructor.
     * @param EntityManagerInterface $em
     * @param CartItemRepository $cartItemRepository
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $em, CartItemRepository $cartItemRepository, Security $security)
    {
        $this->em = $em;
        $this->cartItemRepository = $cartItemRepository;
        $this->security = $security;
    }

    public function cartItemNumber() : int
   {
       $user = $this->security->getUser();

       if($user) {
           return $this->cartItemRepository->count(["owner" => $user]);
       }

       return 2;
   }
}