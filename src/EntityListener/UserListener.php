<?php


namespace App\EntityListener;


use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserListener
{

    private SluggerInterface $slugger;

    private UserPasswordHasherInterface $encoder;

    /**
     * ConferenceEntityListener constructor.
     * @param SluggerInterface $slugger
     * @param UserPasswordHasherInterface $encoder
     */
    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }

    public function prePersist(User $user, LifecycleEventArgs $event) : void
    {
        $password = $this->encoder->hashPassword($user, $user->getPassword());
        $user->setPassword($password)
            ->setIsVerified(false)
            ->setRegisteredAt(new \DateTimeImmutable())
            ->setRoles(["ROLE_USER"])
            ->setAccountMustBeVerifiedBefore((new \DateTimeImmutable("now"))->add(new \DateInterval("P7D")))
            ->computeSlug($this->slugger);
    }

    public function preUpdate(User $user, LifecycleEventArgs $event) : void
    {
        $user->computeSlug($this->slugger);
    }


}