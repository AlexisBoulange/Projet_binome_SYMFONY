<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_ADMIN")
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

            return $this->redirectToRoute('session_index');
        }

        return $this->render('session/new.html.twig', [
            'formAddSession' => $form->createView(),
            'session' => $session,
            'editMode' => $session->getId() !==null
        ]);
    }

    /**
     * @Route("/delete/{id}", name="session_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Session $session): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($session);
        $entityManager->flush();

        return $this->redirectToRoute('session_index');
    }

    /**
     * @Route("/addDuree/{id}", name="add_duree")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addModuleToSession(Request $request, Session $session, EntityManagerInterface $entityManager){


        $form = $this->createForm('App\Form\AteliersType', $session);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // $session = $form->getData();

            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('session_index');
        }

        return $this->render('programmer/addDuree.html.twig', [
            'form' => $form->createView(),
            'session' => $session,
            'editMode' => $session->getId() !==null
        ]);

    }

    /**
     * @Route("/{id}", name="session_show")
     */
    public function show(Session $session): Response
    {
        return $this->render('session/show.html.twig', [
            'session' => $session,
        ]);
    }
}
