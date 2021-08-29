<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use App\Tests\AuthenticatedUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class BookingControllerTest extends WebTestCase
{
    public function testBookPage() {

        $authenticated = new AuthenticatedUser();

        $client = $authenticated->authenticateUser();

        $client->request('GET', '/ads/molestiae-laudantium-omnis-iure-id-voluptas-sed/book');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testBookShowPage(){

        $authenticated = new AuthenticatedUser();

        $client = $authenticated->authenticateUser();

        $client->request('GET', '/booking/4');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    // public function testAdminBookPage(){

    //     $authenticated = new AuthenticatedUser();

    //     $client = $authenticated->authenticateUser();

    //     $client->request('GET', '/admin/bookings/1');

    //     $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    // }

    
    
    // public function testRouteAdsNew(){

        

    //     $client->request('GET', '/ads/new');

    //     $this->assertResponseStatusCodeSame(Response::HTTP_OK);
       
    // }

    // public function testAdEditPage(){
        
    //     $authenticated = new AuthenticatedUser();

    //     $client = $authenticated->authenticateUser();
    //     $client->request('GET', '/ads/molestiae-laudantium-omnis-iure-id-voluptas-sed/edit');
    //     $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    // }

    // public function testAdRemovePage(){
    //     $client = $this->authenticateUser();
    //     $client->request('GET', '/ads/molestiae-laudantium-omnis-iure-id-voluptas-sed/delete');
    //     $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    // }
}