<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\RecapDetails;
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



    #[Route('/order/verify', name: 'order_recap', methods: ['GET', 'POST'])]
    public function preparedorder(CartService $cartService,Request $request): Response
    {

        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
            ]);
        
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            $datetime = new DateTime('now');
            $delivery = $form->get('delivery_address')->getData();
            $deliveryForOrder = $delivery->getDeliveryAddress();
            $order = new Order();
            $reference = $datetime->format('dmY').'-'.uniqid();
            $order->setUser($this->getUser());
            $order->setReference($reference);
            $order->setCreatedAt($datetime);
            $order->setDelivery($deliveryForOrder);
            $order->setPaid(0);
            $order->setMethod('stripe');


            $this->em->persist($order);

            foreach($cartService->getTotal() as $product)
            {
               
                $recapDetails = new RecapDetails();
                $recapDetails->setOrderProduct($order);
                $recapDetails->setQuantity($product['quantity']);
                $recapDetails->setPrice($product['product']->getPrice());
                $recapDetails->setProduct($product['product']->getName());
                $recapDetails->setTotalRecap($product['product']->getPrice() * $product['quantity']);

                $this->em->persist($recapDetails);


            }
            $this->em->flush();

            return $this->render('order/recap.html.twig', [ 
                'method' => $order->getMethod(),
                'recapCart' => $cartService->getTotal(),
                'delivery' => $deliveryForOrder,
                'reference' => $order->getReference(),
                'form' => $form->createView(),
                
            ]);

        }

        return $this->redirectToRoute('app_cart');
   }
    
}
