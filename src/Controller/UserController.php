<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/user")
 * 
 * @IsGranted("ROLE_USER")
 */

class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index")
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy([], ['nom' => 'ASC']);

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }
    /**
     * @Route("/edit/{id}", name="user_edit")
     */
    public function edit(Request $request, User $userConnected): Response
    {
        $id = $userConnected->getId();

        $form = $this->createForm(UserType::class, $userConnected);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_show', ['id' => $id]);
        }

        return $this->render('user/edit.html.twig', [
            'formEditUser' => $form->createView(),
            'id' => $id
        ]);
    }
    /**
     * @Route("/confirm/{id}", name="user_confirmation")
     * @IsGranted("ROLE_ADMIN")
    */
    public function showConfirm(User $user): Response
    {
        return $this->render('user/confirmation.html.twig', [
            'user' => $user,
        ]);
    }
    /**
     * @Route("/delete/{id}", name="user_delete")
     */
    public function delete(User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

       //message add flash de confirmation
        $this->addFlash('success', 'Un utilisateur a bien été supprimé!');

        return $this->redirectToRoute('user_index');
    }
    /**
     * @Route("/{id}", name="user_show")
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
