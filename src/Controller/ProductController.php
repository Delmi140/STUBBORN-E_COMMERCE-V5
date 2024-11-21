<?php

namespace App\Controller;

use App\Entity\SweatShirts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em){}

    #[Route('/products', name: 'shop_index')]
    public function index(): Response
    {
        $products = $this->em->getRepository(SweatShirts::class)->findAll();
        return $this->render('product/index.html.twig',[
            'products' => $products
            ]);
    }


    #[Route('/product/{id<\d+>}', name: 'details_index')]
    public function details(int $id): Response
    {
        $product = $this->em->getRepository(SweatShirts::class)->find($id);
        return $this->render('product/details.html.twig',[
            'product' => $product
            ]);
    }

}
