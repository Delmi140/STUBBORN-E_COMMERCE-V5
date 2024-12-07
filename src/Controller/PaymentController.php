<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Service\CartService;
use App\Entity\SweatShirts;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    private UrlGeneratorInterface $generator;

    public function __construct(UrlGeneratorInterface $generator)
    {
        
        $this->generator = $generator;
    }


    #[Route('/order/create-session-stripe', name:'payment_stripe') ]
    public function stripeChectout(CartService $cartService): RedirectResponse 
    {
        $productStripe = [];

        $recapCart = $cartService->getTotal() ;

        foreach($recapCart as $product){
            $productStripe[] =[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product['product']->getPrice(),
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        ]
                    ],
                    'quantity' => $product['quantity'],
                ];
        }

        Stripe::setApiKey('sk_test_51QSMvsFWdjpCHfh6V2Ee6ArwzBUav7MrBUcd4DirwewCaAhdOx7ch7vF0QpmLIQHOduTsvDdq6ve45lSIrn772nW00hDvqL8gy');
        $checkout_session = Session::create([
            
            'payment_method_types' => ['card'],
            'line_items' => [[
                $productStripe
        ]],
        'mode' => 'payment',
        'success_url' => $this->generator->generate('payment_success', [],UrlGeneratorInterface::ABSOLUTE_URL),
        'cancel_url' => $this->generator->generate('payment_error',[],UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return new RedirectResponse($checkout_session->url);

        
   
    
    }

    #[Route('/order/success/', name:'payment_success')]
    public function stripeSuccess(CartService $cartService): Response {
        return $this->render('order/success.html.twig');

    }

    #[Route('/order/error/', name:'payment_error')]
    public function stripeError(CartService $cartService): Response {
        return $this->render('order/error.html.twig');

    }


}