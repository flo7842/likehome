<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    /**
     * @Route("/", name="homepage")
     */
    public function home(){
        return $this->render(
            'home.html.twig'
        );
    }

    /**
     * @Route("/", name="homepage")
     */
    public function moreReservation(AdRepository $repo)
    {

        $bestAds = $repo->findMoreReservation();
        
        return $this->render(
            'home.html.twig', [
                'bestAds' => $bestAds
            ]
        );
    }

}

?>