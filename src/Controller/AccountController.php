<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\CartService;

final class AccountController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_account')]
    #[IsGranted('ROLE_USER')]
    public function index(CartService $cartService): Response
    {
        // on récupère l’utilisateur connecté
        $user = $this->getUser();

        // récupérer les commandes passées
        $orders = $user->getOrders();

        // on transmet aussi l’état d’activation de l’accès API pour afficher le bon bouton
        return $this->render('account/myAccount.html.twig', [
            'user' => $user,
            'orders' => $orders,
            'cartCount' => $cartService->getTotalQuantity(),
            'apiEnabled' => $user->isApiEnabled(),
        ]);
    }

    #[Route('/account/api/enable', name: 'app_account_api_enable', methods: ['POST'])]
    public function enableApi(Request $request, EntityManagerInterface $entityManager): Response 
    {
        $user = $this->getUser();

        if (!$this->isCsrfTokenValid('toggle-api', $request->request->get('_token'))) {
            return $this->redirectToRoute('app_account');
        }

        $user->setApiEnabled(true);
        $entityManager->flush();

        $this->addFlash('success', 'Accès API activé.');

        return $this->redirectToRoute('app_account');
    }

    #[Route('/account/api/disable', name: 'app_account_api_disable', methods: ['POST'])]
    public function disableApi(Request $request, EntityManagerInterface $entityManager): Response 
    {
        $user = $this->getUser();

        if (!$this->isCsrfTokenValid('toggle-api', $request->request->get('_token'))) {
            return $this->redirectToRoute('app_account');
        }

        $user->setApiEnabled(false);
        $entityManager->flush();

        $this->addFlash('success', 'Accès API désactivé.');

        return $this->redirectToRoute('app_account');
    }

    #[Route('/account/delete', name: 'app_account_delete', methods: ['POST'])]
    public function deleteAccount(Request $request, EntityManagerInterface $em, TokenStorageInterface $tokenStorage): Response 
    {
        if (!$this->isCsrfTokenValid('delete-account', $request->request->get('_token'))) {
        return $this->redirectToRoute('app_account');
        }

        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // invalid session
        $tokenStorage->setToken(null);
        $session = $request->getSession();
        if ($session) {
            $session->invalidate();
        }

        // delete user in db
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_account');
    }

}
