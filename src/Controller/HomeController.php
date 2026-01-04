<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CartService;
use App\Repository\ProductRepository;


final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository, CartService $cartService): Response
    {
        // On récupère tous les produits grâce au repository injecté
        $products = $productRepository->findAll();

        // On envoie la liste au template Twig
        return $this->render('home/index.html.twig', [
            'products' => $products,
            'cartCount' => $cartService->getTotalQuantity(),
        ]);
    }
}
