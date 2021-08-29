<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityControllerTest extends WebTestCase
{

    public function testDisplayLogin(){

        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert alert-danger');
       

    }

    public function testLoginWithBadCredentials(){

        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion !')->form([
            '_username' => 'florian@gmail.com',
            '_password' => 'fakeHash'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Connectez-vous !');

    }

    public function testLoginSuccess(){

        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion !')->form([
            '_username' => 'florianbracq42@gmail.com',
            '_password' => 'password'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('http://localhost/');
        $client->followRedirect();

    }

    
    
}