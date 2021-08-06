<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{


    private EntityManagerInterface $em;

    /**
     * CartController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/cart", name="cart_index")
     * @param SessionInterface $session
     * @return Response
     */
    public function index(SessionInterface $session): Response
    {
        /** @var User $user*/
        $user = $this->getUser();

        $cartItems = [];


        if($user) {
            $cartItems = $user->getCartItems();
        } else {
          $cartItems = $session->get(CartItem::CART_SESSION, []);
        }


        return $this->render('cart/index.html.twig', [
            "products" => $cartItems
        ]);
    }



}
