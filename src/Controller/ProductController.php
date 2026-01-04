<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;
use App\Service\CartService;

final class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'app_product_show', requirements: ['id' => '\d+'])]
    public function show(Product $product, CartService $cartService): Response
    {
        $currentQty = 0;

        if ($this->getUser()) {
            $currentQty = $cartService->getQuantity($product->getId());
        }

        // Grâce au ParamConverter, Symfony récupère le produit automatiquement
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'currentQty' => $currentQty,
            'cartCount' => $cartService->getTotalQuantity(),   
        ]);
    }

    #[Route('/produit/{id}/panier', name: 'app_product_add', methods: ['POST'])]
    public function addToCart(Product $product, Request $request, CartService $cartService): Response 
    {
        // on récupère la quantité (1 par défaut)
        $quantity = max(0, (int) $request->request->get('quantity', 1));

        // mise à jour du panier
        $cartService->add($product->getId(), $quantity);

        $this->addFlash('success', 'Produit ajouté ou mis à jour dans le panier.');
        return $this->redirectToRoute('app_cart');
    }
}
