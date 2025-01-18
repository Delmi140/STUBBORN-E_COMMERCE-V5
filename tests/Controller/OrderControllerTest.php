<?php

namespace App\Tests\Controller;

use App\Controller\OrderController;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;


class OrderControllerTest extends WebTestCase
{
    public function testIndexRedirectsIfNotLoggedIn()
    {
        $client = static::createClient();

        // Créer un utilisateur dans la base de données
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setName('John');
        $user->setDeliveryAddress('13 Main St');
        $user->setPassword('password');
        $user->setRoles(['ROLE_USER']);
        $user->setVerified(true);

        // Sauvegarder l'utilisateur dans la base de données
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);
        $entityManager->persist($user);
        $entityManager->flush(); // Sauvegarder l'utilisateur et attribuer un identifiant


        

        // Vérifier que l'utilisateur existe et qu'il a un identifiant
        $this->assertNotNull($user->getId());


        // Rafraîchir l'entité pour s'assurer que l'utilisateur a un identifiant
        $entityManager->refresh($user); 

        // Utiliser l'utilisateur persistant pour la connexion
        $client->loginUser($user);

        // Simuler un service de panier avec un total fictif sous forme de tableau
        $cartServiceMock = $this->createMock(CartService::class);
        $cartServiceMock->method('getTotal')->willReturn(['total' => 200]);

        $client->getContainer()->set(CartService::class, $cartServiceMock);

        $crawler = $client->request('GET', '/order/verify');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorTextContains('order_recap', '200');

    }

    public function testIndexRendersFormWhenLoggedIn()
    {
        $client = static::createClient();

        // Créer un utilisateur dans la base de données
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setName('John');
        $user->setDeliveryAddress('13 Main St');
        $user->setPassword('password');
        $user->setRoles(['ROLE_USER']);
        $user->setVerified(true);

        // Sauvegarder l'utilisateur dans la base de données
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);
        $entityManager->persist($user);
        $entityManager->flush(); // Sauvegarder l'utilisateur et attribuer un identifiant

        

        // Vérifier que l'utilisateur existe et qu'il a un identifiant
        $this->assertNotNull($user->getId());


        // Rafraîchir l'entité pour s'assurer que l'utilisateur a un identifiant
        $entityManager->refresh($user); 

        // Utiliser l'utilisateur persistant pour la connexion
        $client->loginUser($user);

        // Simuler un service de panier avec un total fictif
        $cartServiceMock = $this->createMock(CartService::class);
        $cartServiceMock->method('getTotal')->willReturn(['total' => 200]);

        $client->getContainer()->set(CartService::class, $cartServiceMock);

        $crawler = $client->request('GET', '/order/verify');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorTextContains('order_recap', '200');
    }

    public function testPrepareOrderRedirectsIfNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET', '/order/verify');

        $this->assertResponseRedirects('/login', 302);
    }

    public function testPrepareOrderRendersRecapWhenLoggedIn()
    {
        $client = static::createClient();

        // Simuler un utilisateur connecté
        $client->loginUser($this->createMockUser());

        // Simuler un service de panier avec un total fictif
        $cartServiceMock = $this->createMock(CartService::class);
        $cartServiceMock->method('getTotal')->willReturn(['total' => 200]); // Retourner un tableau

        $client->getContainer()->set(CartService::class, $cartServiceMock);

        $crawler = $client->request('GET', '/order/verify');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form'); // Vérifie qu'un formulaire est rendu
        $this->assertSelectorTextContains('order_recap', '200'); // Vérifie que le total est affiché
    }

    private function createMockUser()
    {
        

    $client = static::createClient();
    // Créer un utilisateur
    $user = new User();
    $user->setEmail('test@example.com');
    $user->setName('John');
    $user->setDeliveryAddress('13 Main St');
    $user->setPassword('password');
    $user->setRoles(['ROLE_USER']);
    $user->setVerified(true);

    // Sauvegarder l'utilisateur dans la base de données pour s'assurer qu'il ait un identifiant
    $entityManager = $client->getContainer()->get(EntityManagerInterface::class);
    $entityManager->persist($user);
    $entityManager->flush();

    return $user;
    }
}