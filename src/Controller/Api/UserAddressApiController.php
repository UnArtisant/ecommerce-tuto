<?php


namespace App\Controller\Api;


use App\Entity\UserAddress;
use App\Repository\UserAddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Annotation\Groups;

class UserAddressApiController extends AbstractController
{

    private UserAddressRepository $userAddressRepository;

    private EntityManagerInterface $em;

    /**
     * UserAddressApiController constructor.
     * @param UserAddressRepository $userAddressRepository
     */
    public function __construct(UserAddressRepository $userAddressRepository, EntityManagerInterface $em)
    {
        $this->userAddressRepository = $userAddressRepository;
        $this->em = $em;
    }

    /**
     * @Route("/api/v1/setDefaultAddress/{id}", methods={"POST"})
     * @param UserAddress $address
     * @param Request $request
     * @return JsonResponse
     */
    public function setDefaultAddress(UserAddress $address, Request $request): JsonResponse
    {
        try {
            $user = $this->getUser();

            if ($address->getOwner()->getId() !== $user->getId() || !$user) {
                throw new \Exception("You can't access this ressource", 403);
            }

            $defaultAddress = $this->userAddressRepository->getDefeaultAddress($user->getId());

            if ($defaultAddress !== $address) {
                $defaultAddress->setIsDefaultAddress(false);

                $address->setIsDefaultAddress(true);
                $this->em->flush();
            }

            return new JsonResponse(["message" => "Default Address changed"]);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], $e->getCode());
        }

    }

}