<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\OrderItem;
use App\Entity\User;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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
     * @param Session $session
     * @return Response
     */
    public function index(Session $session): Response
    {
        /** @var User $user*/
        $user = $this->getUser();

        $cartItems = [];


        if($user) {
            $cartItems = $user->getCartItems();
        } else {

        }
        dump($cartItems);

        return $this->render('cart/index.html.twig', [
            "products" => $cartItems
        ]);
    }


    /**
     * @Route("cart/delete/{id}", name="card_remove_item")
     */
    public function removeItem(CartItem $cartItem) {
      dd($cartItem);
    }

}
