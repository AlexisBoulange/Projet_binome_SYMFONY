<?php

namespace App\Controller;

use App\Entity\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/session")
 */
class SessionController extends AbstractController
{
    /**
     * @Route("/", name="session_index")
     */
    public function index(): Response
    {
        $sessions = $this->getDoctrine()
            ->getRepository(Session::class)
            ->findBy([], ['dateD' => 'ASC']);

        return $this->render('session/index.html.twig', [
            'sessions' => $sessions
        ]);
    }
    /**
     * @Route("/new", name="session_add")
     * @Route("/edit/{id}", name="session_edit")
     */
    public function new(Request $request, Session $session = null): Response
    {
        if (!$session) {
            $session = new Session();
        }

        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $session = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('session');
        }

        return $this->render('session/new.html.twig', [
            'formAddsession' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="session_delete")
     */
    public function delete(Session $session): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($session);
        $entityManager->flush();

        return $this->redirectToRoute('session');
    }

    /**
     * @Route("/{id}", name="session_show")
     */
    public function show(): Response
    {
        return $this->render('session/show.html.twig', [
            'session' => 'session',
        ]);
    }
}
