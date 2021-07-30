<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SignUp extends AbstractController
{

    private EntityManagerInterface $em;

    private TokenGeneratorInterface $generator;

    private EmailService $emailService;

    /**
     * SignUp constructor.
     * @param EntityManagerInterface $em
     * @param TokenGeneratorInterface $generator
     * @param EmailService $emailService
     */
    public function __construct(EntityManagerInterface $em,
                                TokenGeneratorInterface $generator,
                                EmailService $emailService)
    {
        $this->em = $em;
        $this->generator = $generator;
        $this->emailService = $emailService;
    }

    /**
     * @Route("/sign-up", name="sercurity.signup")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request): Response
    {

        if($this->getUser()) {
            return $this->redirectToRoute("product_index");
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $registrationToken = $this->generator->generateToken();
            $user->setRegistrationToken($registrationToken);

            $this->em->beginTransaction();
            try {
                $this->em->persist($user);
                $this->em->flush();
                $this->em->commit();
            } catch (\Exception $e) {
                $this->em->rollback();
                throw $e;
            }

            $this->emailService->send([
                "recipient_email" => $form->get("email")->getData(),
                "subject" => "UnArtisan | Confirmation de compte",
                "html_templates" => "emails/registration/_registration_user.html.twig",
                "context" => [
                    "userId" => $user->getId(),
                    "registrationToken" => $registrationToken,
                    "tokenLifeTime" => $user->getAccountMustBeVerifiedBefore()
                        ->format("d/m/Y à H:i"),
                ]
            ]);

            $this->addFlash("success", "Your account have been created!");
            return $this->redirectToRoute("app_login");
        }

        return $this->render("security/sign_up.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/account-verifie/{id<\d+>}/{token}", name="app_verifie_user")
     * @param User $user
     * @param string $token
     * @return Response
     */
    public function accountVerifie(User $user, string $token): Response
    {
        if($user->getRegistrationToken() !== $token ||
            !$user->getRegistrationToken() ||
            $this->isNotRequestedInTime($user->getAccountMustBeVerifiedBefore())
        ) {
            throw new AccessDeniedException("Accès interdit");
        }

        $user->setRegistrationToken(null)
            ->setIsVerified(true)
            ->setAccountVerifiedAt(new \DateTimeImmutable("now"))
        ;

        $this->em->flush();

        $this->addFlash("success", "Votre compte a bien été vérifié");

        return $this->redirectToRoute("app_login");
    }

    /**
     * @param \DateTimeImmutable $date
     * @return bool
     */
    protected function isNotRequestedInTime(\DateTimeImmutable $date): bool
    {
        return new \DateTimeImmutable("now") > $date;
    }
}