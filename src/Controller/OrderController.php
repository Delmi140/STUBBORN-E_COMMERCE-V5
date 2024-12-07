<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\RecapDetails;
use App\Entity\User;
use App\Form\OrderType;
use App\Service\CartService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OrderController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em )
    {
        $this->em = $em;
    }


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



    #[Route('/order/verify', name: 'order_recap' )]
    public function prepareOrder(CartService $cartService,Request $request,): Response
    {

    if(!$this->getUser()) {
        return $this->redirectToRoute('app_login');

    }

    $form = $this->createForm(OrderType::class, null, [
        'user' => $this->getUser(),
        
        ]);

    
    $user = $this->getUser();
    $recapCart = $cartService->getTotal();
   
  
   
  

    return $this->render('order/recap.html.twig', [ 
            'form' => $form->createView(),
            'recapCart' => $recapCart,
            
            
           
            
           
          
            
        ]);
       
    }

}