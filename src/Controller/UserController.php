<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/user")
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
     * @Route("/new", name="user_add")
     * @Route("/edit/{id}", name="user_edit")
     */
    public function new(Request $request, User $user = null): Response
    {
        if(!$user){
            $user = new User();
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user/new.html.twig', [
            'formAdduser' => $form->createView(),
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

        return $this->redirectToRoute('user');
    }

    /**
     * @Route("/{id}", name="user_show")
     */
    public function show(): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => 'user',
        ]);
    }
}
