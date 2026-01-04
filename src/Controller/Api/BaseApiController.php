<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseApiController extends AbstractController
{
    protected function denyIfApiDisabled(): ?Response
    {
        $user = $this->getUser();

        if (!$user || !method_exists($user, 'isApiEnabled') || !$user->isApiEnabled()) {
            return $this->json(['error' => 'API access disabled'], 403);
        }

        return null;
    }
}
