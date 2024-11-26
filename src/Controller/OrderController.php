<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OrderController extends AbstractController
{

    #[Route('/order/create', name: 'order_create')]
    public function index(CartService $cartService): Response
    {

        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
            ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'recapCart' => $cartService->getTotal()
        ]);
    }

    #[Route('/order/verify', name: 'order_prepare', methods: ['POST'])]
    public function preparedorder(): Response
    {
        return $this->render('/order/recap.html.twig');
    }
    
}
