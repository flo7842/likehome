<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use App\Service\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user/{page<\d+>?1}", name="admin_user_index")
     */
    public function index(UserRepository $repo, $page, Pagination $pagination)
    {
        $pagination->setEntityClass(User::class)
                    ->setLimit(10)
                    ->setPages($page);

        return $this->render('admin/user/show.html.twig', [
            'pagination' => $pagination
        ]);
    }


    /**
     * Allows you to edit a user
     *
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     *
     * @return Response
     *
     */
    public function edit(User $user, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation n°{$user->getId()} a bien été modifiée"
            );

            return $this->redirectToRoute("admin_user_index");
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
