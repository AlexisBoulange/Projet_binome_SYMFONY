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
     * @Route("/calendar", name="session_calendar", methods={"GET"})
     */
    public function calendar(): Response
    {
        return $this->render('session/calendar.html.twig');
    }

    /**
     * @Route("/new", name="session_add")
     * @Route("/edit/{id}", name="session_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, Session $session = null): Response
    {
            $session = new Session();

        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //On récupère nos date de début et de fin
            $dateD = $form->get('dateD')->getData();
            $dateF = $form->get('dateF')->getData();
            
            //Si la date de début est supérieure à la date de fin on envoie un message d'erreur
            if($dateD > $dateF){
                $this->addFlash('warning', 'La date de fin ne peut pas finir avant la date de début !');
            }else{    
                $session = $form->getData();
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($session);
                $entityManager->flush();
                
                return $this->redirectToRoute('session_index');
            }
        }

        return $this->render('session/new.html.twig', [
            'formAddSession' => $form->createView(),
            'session' => $session,
            'editMode' => $session->getId() !==null
        ]);
    }
    /**
     * @Route("/confirm/{id}", name="session_confirmation")
     * @IsGranted("ROLE_ADMIN")
    */
    public function showConfirm(Session $session): Response
    {
        return $this->render('session/confirmation.html.twig', [
            'session' => $session,
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

       //message add flash de confirmation
       $this->addFlash('success', 'La session a bien été supprimée!');

       return $this->redirectToRoute('session_index');
    }

    /**
     * @Route("/addDuree/{id}", name="add_duree")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addModuleToSession(Request $request, Session $session, EntityManagerInterface $entityManager){

        $id = $session->getId();

        $form = $this->createForm('App\Form\AteliersType', $session);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // $session = $form->getData();

            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('session_show', ['id' => $id]);
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
