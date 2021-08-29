<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatedUser extends WebTestCase
{
    public function authenticateUser(){
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('florianbracq42@gmail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        
        return $client;
    }
}