<?php

namespace App\Controller;

use App\Entity\SweatShirts;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {

        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getTotal()
            ]);
    }


    #[Route('/product/add/{id<\d+>}', name: 'cart_add')]
    public function addToCart(CartService $cartService, SweatShirts $sweatShirts): Response
    {
        $cartService->addToCart($sweatShirts->getId());

        return $this->redirectToRoute('app_cart');
    }


    #[Route('/product/remove/{id<\d+>}', name: 'cart_remove')]
    public function removeToCart(CartService $cartService, SweatShirts $sweatShirts): Response
    {
        $cartService->removeToCart($sweatShirts->getId());

        return $this->redirectToRoute('app_cart');
    }





    #[Route('/product/removeAll', name: 'cart_removeAll')]
    public function removeAll(CartService $cartService,): Response
    {
        $cartService->removeCartAll();

        return $this->redirectToRoute('shop_index');
    }

}