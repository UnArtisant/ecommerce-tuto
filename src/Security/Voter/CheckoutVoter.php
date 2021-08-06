<?php

namespace App\Security\Voter;

use App\Entity\CartItem;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CheckoutVoter extends Voter
{

    private RequestStack $request;

    /**
     * CheckoutVoter constructor.
     * @param RequestStack $request
     */
    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['IS_CART_EMPTY']);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        $session = $this->request->getSession();

        if ($user) {
            $cartItem = $user->getCartItems()->toArray();
        } else {
            $cartItem = $session->get(CartItem::CART_SESSION, []);
        }

        if(!empty($cartItem)) {
            return true;
        }

        return false;
    }
}
