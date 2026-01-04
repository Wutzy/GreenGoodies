<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Order;
use App\Service\CartService;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart', methods: ['GET'])]
    public function index(CartService $cartService, EntityManagerInterface $em): Response 
    {
        $cart = $cartService->getCart(); // [productId => quantity]

        $cartItems = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $em->getRepository(Product::class)->find($productId);

            // si le produit n’existe plus, on le retire du panier
            if (!$product) {
                $cartService->add((int) $productId, 0);
                continue;
            }

            $lineTotal = $product->getPrice() * $quantity;
            $total += $lineTotal;

            $cartItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'total' => $lineTotal,
            ];
        }

        return $this->render('cart/myCart.html.twig', [
            'cartCount' => $cartService->getTotalQuantity(),
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    #[Route('/cart/clear', name: 'app_cart_clear', methods: ['POST'])]
    public function clear(Request $request, CartService $cartService): Response 
    {
        if (!$this->isCsrfTokenValid('cart-clear', $request->request->get('_token'))) {
            return $this->redirectToRoute('app_cart');
        }

        $cartService->clear();
        $this->addFlash('success', 'Panier vidé.');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/checkout', name: 'app_cart_checkout', methods: ['POST'])]
    public function checkout(Request $request, CartService $cartService, EntityManagerInterface $em): Response 
    {
        if (!$this->isCsrfTokenValid('cart-checkout', $request->request->get('_token'))) {
            return $this->redirectToRoute('app_cart');
        }

        $user = $this->getUser();
        $order = new Order();
        $order->setUser($user);

        foreach ($cartService->getCart() as $productId => $qty) {
            $product = $em->getRepository(Product::class)->find($productId);
            if (!$product) {
                continue;
            }
            $item = new OrderItem();
            $item->setCustomerOrder($order);
            $item->setProduct($product);
            $item->setQuantity($qty);
            $item->setPrice($product->getPrice());
            $em->persist($item);
        }

        $em->persist($order);
        $em->flush();

        // Clear cart after order validate
        $cartService->clear();
        $this->addFlash('success', 'Commande enregistrée.');
        return $this->redirectToRoute('app_account');
    }

    #[Route('/_cart/count', name: 'app_cart_count', methods: ['GET'])]
    public function count(CartService $cartService): Response
    {
        return $this->render('cart/_count.html.twig', [
            'cartCount' => $cartService->getTotalQuantity(),
        ]);
    }
}
