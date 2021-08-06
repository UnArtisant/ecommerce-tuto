<?php


namespace App\EntityListener;


use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Products;
use App\Repository\CartItemRepository;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\Events;

class OrderListener
{

    private EntityManagerInterface $em;
    private CartItemRepository $cartItemRepository;
    private Security $security;
    private RequestStack $request;


    /**
     * OrderListener constructor.
     * @param EntityManagerInterface $em
     * @param CartItemRepository $cartItemRepository
     * @param Security $security
     * @param RequestStack $request
     */
    public function __construct(EntityManagerInterface $em, CartItemRepository $cartItemRepository, Security $security, RequestStack $request)
    {
        $this->em = $em;
        $this->cartItemRepository = $cartItemRepository;
        $this->security = $security;
        $this->request = $request;
    }

    public function prePersist(Order $order, LifecycleEventArgs $args): void
    {

        if ($order instanceof Order) {

            $user = $this->security->getUser();
            $session = $this->request->getSession();

            if ($user) {
                $cartItem = $user->getCartItems();
            } else {
                $cartItem = $session->get(CartItem::CART_SESSION, []);
            }

            $total = 0;
            try {
                foreach ($cartItem as $item) {

                    $orderItem = new OrderItem();
                    if ($item instanceof CartItem) {
                        $orderItem->setProduct($item->getProduct())
                            ->setQuantity($item->getQuantity())
                            ->setOrderTransaction($order)
                            ->setProductName($item->getProduct()->getName())
                            ->setUnitPrice($item->getProduct()->getPrice());

                       $total += $item->getProduct()->getPrice() * $item->getQuantity();

                        $this->em->remove($item);
                    } else {
                        $orderItem->setProduct($this->em->getReference(Products::class, $item["id"]))
                            ->setQuantity($item["quantity"])
                            ->setOrderTransaction($order)
                            ->setProductName($item["name"])
                            ->setUnitPrice($item["price"]);

                        $total += $item["price"] * $item["quantity"];
                    }
                    $this->em->persist($orderItem);
                }
                $order->setTotalPrice($total);
                $session->set(CartItem::CART_SESSION, []);
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

}