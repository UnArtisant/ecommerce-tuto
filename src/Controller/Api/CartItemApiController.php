<?php


namespace App\Controller\Api;


use App\Entity\CartItem;
use App\Entity\OrderItem;
use App\Entity\Products;
use App\Repository\CartItemRepository;
use App\Repository\ProductsRepository;
use App\Service\DeserializeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartItemApiController extends AbstractController
{

    private ProductsRepository $productRepository;

    private EntityManagerInterface $em;

    private DeserializeService $deserialiseService;

    private CartItemRepository $cartItemRepository;

    /**
     * OrderItemApiController constructor.
     * @param ProductsRepository $productRepository
     * @param EntityManagerInterface $em
     * @param CartItemRepository $cartItemRepository
     * @param DeserializeService $deserialiseService
     */
    public function __construct(ProductsRepository $productRepository,
                                EntityManagerInterface $em,
                                DeserializeService $deserialiseService,
                                CartItemRepository $cartItemRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->em = $em;
        $this->deserialiseService = $deserialiseService;
        $this->cartItemRepository = $cartItemRepository;
    }


    /**
     * @Route("/api/v1/cart-item", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function post(Request $request): JsonResponse
    {
        try {
            $user = $this->getUser();
            $data = $request->getContent();

            ["product" => $product, "quantity" => $quantity] = json_decode($data, true);
            $product = $this->productRepository->find($product);

            if (!$product) {
                throw new \Exception("Product Not found", 404);
            }

            if ($product->getStock() <= 0 ||
                $product->getStatus() === Products::PRODUCT_HIDDEN ||
                $product->getStock() < $quantity) {
                throw new \Exception("Access denied! You can't add this product", 403);
            }


            if ($user) {
                $cartItem = $this->cartItemRepository->getExistingCartItem($product->getId(), $user->getId());

                if (!$cartItem) {
                    $cartItem = new CartItem();
                    $cartItem->setQuantity($quantity)
                        ->setProduct($product)
                        ->setOwner($user);
                    $this->em->persist($cartItem);
                } else {
                    $quantity = $quantity + $cartItem->getQuantity();

                    if ($product->getStock() < $quantity){
                        throw new \Exception("Access denied!Quantity is superior to stock", 403);
                    }
                    $cartItem->setQuantity($quantity);
                }

                $this->em->flush();

                $cartArray = $this->deserialiseService->deserialiseObject($cartItem, ['groups' => ['read:cart']]);
                $cartArray["message"] = "Product add to the cart";
                return new JsonResponse($cartArray);
            }

            return new JsonResponse("coucou");

        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], $e->getCode() ? $e->getCode() : 500);
        }

    }

}