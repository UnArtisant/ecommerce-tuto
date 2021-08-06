<?php


namespace App\Service;


use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class Globaltwig
{

    private EntityManagerInterface $em;

    private CartItemRepository $cartItemRepository;

    private Security $security;

    private RequestStack $requestStack;

    /**
     * Globaltwig constructor.
     * @param EntityManagerInterface $em
     * @param CartItemRepository $cartItemRepository
     * @param Security $security
     * @param RequestStack $requestStack
     */
    public function __construct(EntityManagerInterface $em,
                                CartItemRepository $cartItemRepository,
                                Security $security,
                                RequestStack $requestStack
    )
    {
        $this->em = $em;
        $this->cartItemRepository = $cartItemRepository;
        $this->security = $security;
        $this->requestStack = $requestStack;
    }

    public function cartItemNumber() : int
   {
       $user = $this->security->getUser();

       if($user) {
           return $this->cartItemRepository->count(["owner" => $user]);
       }

       $session = $this->requestStack->getSession();

       return  count($session->get(CartItem::CART_SESSION, []));
   }

   public function cartItem()
   {
       $user = $this->security->getUser();

       $session = $this->requestStack->getSession();

       if($user) {
           return $user->getCartItems();
       } else {
           return $session->get(CartItem::CART_SESSION, []);
       }
   }
}