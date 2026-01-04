<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }
    
    public function add(int $productId, int $quantity): void
    {
        $cart = $this->session->get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
        }

        $this->session->set('cart', $cart);
    }

    public function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    public function clear(): void
    {
        $this->session->remove('cart');
    }

    public function getQuantity(int $productId): int
    {
        $cart = $this->getCart();
        return (int) ($cart[$productId] ?? 0);
    }

    public function getTotalQuantity(): int
    {
        return array_sum($this->getCart());
    }
}

