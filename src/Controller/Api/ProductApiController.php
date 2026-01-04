<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductApiController extends BaseApiController
{
    #[Route('api/products', name: 'products_list', methods: ['GET'])]
    public function list(ProductRepository $repo): Response
    {
        if ($resp = $this->denyIfApiDisabled()) {
            return $resp;
        }

        $products = $repo->findAll();

        // renvoie uniquement les champs exposés (via groups)
        return $this->json($products, 200, [], ['groups' => ['product:read']]);
    }
}
