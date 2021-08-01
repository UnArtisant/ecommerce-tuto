<?php


namespace App\Controller;


use App\Repository\UserAddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    private UserAddressRepository $userAddressRepository;

    /**
     * ProfileController constructor.
     * @param UserAddressRepository $userAddressRepository
     */
    public function __construct(UserAddressRepository $userAddressRepository)
    {
        $this->userAddressRepository = $userAddressRepository;
    }

    /**
     * @Route("/profile", name="profile_index")
     * @return Response
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $defaultAddress = $this->userAddressRepository->getDefeaultAddress($user->getId());

        return $this->render("profile/index.html.twig", [
            "user" => $user,
            "defaultAddress" => $defaultAddress,
            "addresses" => $this->userAddressRepository->findBy(["owner" => $user])
        ]);
    }

}