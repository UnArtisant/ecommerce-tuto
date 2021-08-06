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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function post(Request $request, SessionInterface $session): JsonResponse
    {
        $this->em->beginTransaction();
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

                    if ($product->getStock() < $quantity) {
                        throw new \Exception("Access denied!Quantity is superior to stock", 403);
                    }
                    $cartItem->setQuantity($quantity);
                }

                $this->em->flush();

                $cartArray = $this->deserialiseService->deserialiseObject($cartItem, ['groups' => ['read:cart']]);
                $cartArray["message"] = "Product add to the cart";
                $this->em->commit();
                return new JsonResponse($cartArray);
            }

            $cart = $session->get(CartItem::CART_SESSION, []);
            $product_id = $product->getId();

            if (array_key_exists($product_id, $cart)) {
                $cart[$product_id]["quantity"] += $quantity;
                $cart[$product_id]["total"] += $product->getPrice() * $quantity;
            } else {
                $product = [
                    "id" => $product_id,
                    "quantity" => $quantity,
                    "price" => $product->getPrice(),
                    "image" => $product->getImage(),
                    "total" => $product->getPrice() * $quantity,
                    "name" => $product->getName(),
                    "stock" => $product->getStock(),
                ];

                $cart[$product_id] = $product;
            }

            $session->set(CartItem::CART_SESSION, $cart);

            return new JsonResponse(["message" => "Product add to the cart"]);

        } catch (\Exception $e) {
            $this->em->rollback();
            return new JsonResponse(["error" => $e->getMessage()], $e->getCode() ? $e->getCode() : 500);
        }

    }

    /**
     * @Route("/api/v1/cart-item/{id}", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function updateQuantity(int $id,Request $request,SessionInterface $session) : JsonResponse
    {
        $this->em->beginTransaction();
        try {
            $user = $this->getUser();
            ["quantity" => $quantity] = json_decode($request->getContent(), true);
            $quantity = intval($quantity);
            if($user) {
                $item = $this->cartItemRepository->getExistingCartItem($id, $user->getId());

                if(!$item) {
                    throw new \Exception("Item not found", 404);
                }

                $item->setQuantity($quantity);
                $this->em->flush();
            } else {
                $cards = $session->get(CartItem::CART_SESSION, []);
                if(array_key_exists($id, $cards)) {
                    $cards[$id]["quantity"] = $quantity;
                } else {
                    throw new \Exception("Item not found", 500);
                }
                $session->set(CartItem::CART_SESSION, $cards);
            }

            $this->em->commit();
            return new JsonResponse(["message" => "item updated successfully"]);
        } catch (\Exception $e) {
            $this->em->rollback();
            return new JsonResponse(["error" => $e->getMessage()], $e->getCode() ? $e->getCode() : 500);
        }
    }

    /**
     * @Route("/api/v1/cart-item/{id}", methods={"DELETE"})
     * @param int $id
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function delete(int $id, SessionInterface $session): JsonResponse
    {
        $this->em->beginTransaction();
        try {
            $user = $this->getUser();

            if (!$id) {
                throw new \Exception("Product not found", 404);
            }

            $id = intval($id);

            if ($user) {
                $item = $this->cartItemRepository->getExistingCartItem($id, $user->getId());
                if (!$item) {
                    throw new \Exception("Item not found", 404);
                }
                $this->em->remove($item);
                $this->em->flush();
            } else {
                $cart = $session->get(CartItem::CART_SESSION,[]);
                if (!array_key_exists($id, $cart)) {
                    throw new \Exception("Item not found", 404);
                }
                unset($cart[$id]);
                $session->set(CartItem::CART_SESSION, $cart);
            }

            $this->em->commit();
            return new JsonResponse(["message" => "Item deleted"]);
        } catch (\Exception $e) {
            $this->em->rollback();
            return new JsonResponse(["error" => $e->getMessage()], $e->getCode() ? $e->getCode() : 500);
        }
    }


}