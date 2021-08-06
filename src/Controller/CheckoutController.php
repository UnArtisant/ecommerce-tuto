<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderAddress;
use App\Entity\User;
use App\Form\OrderType;
use App\Repository\UserAddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{

    private UserAddressRepository $userAddressRepository;

    private EntityManagerInterface $em;

    /**
     * OrderAddressType constructor.
     * @param UserAddressRepository $userAddressRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(UserAddressRepository $userAddressRepository,
                                EntityManagerInterface $em)
    {
        $this->userAddressRepository = $userAddressRepository;
        $this->em = $em;
    }

    /**
     * @Route("/checkout", name="checkout_index")
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index(Request $request): Response
    {

        $this->denyAccessUnlessGranted("IS_CART_EMPTY");

        /** @var ?User $user*/
        $user = $this->getUser();

        $address = $this->defaultData($user);
        $order = $this->defaultOrderData($user, $address);
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->beginTransaction();
            try {
                $this->em->persist($address);
                $this->em->persist($order);
                $this->em->flush();
                $this->em->commit();

                $this->addFlash("success", "Order placed successfully");
                return $this->redirectToRoute("checkout_index");
            } catch (\Exception $e) {
                $this->em->rollback();
                throw $e;
            }
        }

        return $this->render('checkout/index.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @param User|null $user
     * @return OrderAddress
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function defaultData (?User $user) : OrderAddress
    {
        if($user) {
            /** @var OrderAddress $address*/
            $address = $this->userAddressRepository->getDefeaultAddress($user->getId());
            if(!$address) {
                return new OrderAddress();
            }
            return (new OrderAddress())->setCity($address->getCity())
                ->setCountry($address->getCountry())
                ->setState($address->getState())
                ->setAddress($address->getAddress())
                ->setZipcode($address->getZipcode())
                ;
        }
        return new OrderAddress();
    }

    /**
     * @param User|null $user
     * @param OrderAddress $address
     * @return Order
     */
    private function defaultOrderData(?User $user, OrderAddress $address) : Order
    {
        $order = new Order();
        $address->setOwner($order);
        if($user) {
            return $order
                ->setLastname($user->getLastname())
                ->setFirstname($user->getFirstname())
                ->setEmail($user->getEmail())
                ->setOrderAddress($address)
                ;
        }
        return $order->setOrderAddress($address);
    }
}
