<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserAddress;
use App\Form\UserAddressType;
use App\Repository\UserAddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAddressController extends AbstractController
{

    private EntityManagerInterface $em;

    private UserAddressRepository $userAddressRepository;

    /**
     * UserAddressController constructor.
     * @param EntityManagerInterface $em
     * @param UserAddressRepository $userAddressRepository
     */
    public function __construct(EntityManagerInterface $em, UserAddressRepository $userAddressRepository)
    {
        $this->em = $em;
        $this->userAddressRepository = $userAddressRepository;
    }

    /**
     * @Route("/profile/add/address", name="user_address")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();

        $userAddress = new UserAddress();
        $userAddress->setOwner($user);

        $form = $this->createForm(UserAddressType::class, $userAddress);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->beginTransaction();
            try {
                $activeAddress = $this->userAddressRepository->getDefeaultAddress($user->getId());
                if ($userAddress->getIsDefaultAddress() && $activeAddress) {
                    $activeAddress->setIsDefaultAddress(false);
                } else if (!$activeAddress) {
                    $userAddress->setIsDefaultAddress(true);
                }
                $this->em->persist($userAddress);
                $this->em->flush();
                $this->em->commit();
                $this->addFlash("success", "Address add to the profile");
                return $this->redirectToRoute("profile_index");
            } catch (\Exception $e) {
                $this->em->rollback();
                throw $e;
            }
        }

        return $this->render('user_address/index.html.twig', [
            "form" => $form->createView()
        ]);
    }

}
