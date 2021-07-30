<?php


namespace App\Controller;


use App\Entity\Products;
use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    private EntityManagerInterface $em;

    /**
     * ProductController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="product_index")
     * @param Request $request
     * @param PaginationService $pagination
     * @return Response
     */
    public function index(Request $request, PaginationService $pagination): Response
    {

        $query   = $this->em->getRepository(Products::class)->getQuery();
        $results = $pagination->paginate($query, $request, 10);

        return $this->render("product/index.html.twig", [
            "products" => $results,
            'lastPage' => $pagination->lastPage($results)
        ]);
    }

    /**
     * @Route("/products/{slug}", name="product_show")
     * @param Products $product
     * @return Response
     */
    public function show(Products $product): Response
    {
        /** @var ReviewRepository $reviewRepository*/
        $reviewRepository = $this->em->getRepository(Review::class);

        return $this->render("product/show.html.twig", [
            "product" => $product,
            "rating" => $reviewRepository->getRating($product->getId()),
            "numberReview" => count($product->getReviews())
        ]);
    }
}