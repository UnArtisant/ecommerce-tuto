<?php


namespace App\EntityListener;


use App\Entity\Products;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductsListener
{

    private SluggerInterface $slugger;

    /**
     * ConferenceEntityListener constructor.
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Products $user, LifecycleEventArgs $event) {
        $user->computeSlug($this->slugger);
    }

    public function preUpdate(Products $user, LifecycleEventArgs $event) {
        $user->computeSlug($this->slugger);
    }

}