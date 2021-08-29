<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use App\Tests\AuthenticatedUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class AdControllerTest extends WebTestCase
{
    public function testAdPage() {
        $client = static::createClient();
        $client->request('GET', '/ads');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAdFormPage(){
        $client = static::createClient();
        $client->request('GET', '/ads/new');
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testRouteAdsNew(){

        $authenticated = new AuthenticatedUser();

        $client = $authenticated->authenticateUser();

        $client->request('GET', '/ads/new');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
       
    }

    public function testAdEditPage(){
        
        $authenticated = new AuthenticatedUser();

        $client = $authenticated->authenticateUser();
        $client->request('GET', '/ads/molestiae-laudantium-omnis-iure-id-voluptas-sed/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

}