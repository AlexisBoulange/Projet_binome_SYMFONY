<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/stagiaire")
 */
class StagiaireController extends AbstractController
{
    /**
     * @Route("/", name="stagiaire")
     */
    public function index(): Response
    {
        $stagiaires = $this->getDoctrine()
            ->getRepository(Stagiaire::class)
            ->findBy([], ['nom' => 'ASC']);

        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires
        ]);
    }
    /**
     * @Route("/new", name="stagiaire_add")
     * @Route("/edit/{id}", name="stagiaire_edit")
     */
    public function new(Request $request, Stagiaire $stagiaire = null): Response
    {
        if(!$stagiaire){
            $stagiaire = new Stagiaire();
        }

        $form = $this->createForm(StagiaireType::class, $stagiaire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
            $stagiaire = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stagiaire);
            $entityManager->flush();

            return $this->redirectToRoute('stagiaire');
        }

        return $this->render('stagiaire/new.html.twig', [
            'formAddstagiaire' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="stagiaire_show")
     */
    public function show(): Response
    {
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => 'stagiaire',
        ]);
    }
}
