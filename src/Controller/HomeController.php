<?php

namespace App\Controller;

use App\Entity\SweatShirts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em){}

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        $products = $this->em->getRepository(SweatShirts::class)->findByIds([1,4,9]);
        return $this->render('home/index.html.twig', [
            'products' => $products
        ]);
    }
}
