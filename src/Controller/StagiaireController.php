<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/stagiaire")
 * @IsGranted("ROLE_USER")
 */
class StagiaireController extends AbstractController
{
    /**
     * @Route("/", name="stagiaire_index")
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
     * @IsGranted("ROLE_ADMIN")
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

            return $this->redirectToRoute('stagiaire_index');
        }

        return $this->render('stagiaire/new.html.twig', [
            'formAddStagiaire' => $form->createView(),
            'stagiaire' => $stagiaire,
            'editMode' => $stagiaire->getId() !==null
        ]);
    }

    /**
     * @Route("/delete/{id}", name="stagiaire_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Stagiaire $stagiaire): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($stagiaire);
        $entityManager->flush();

        return $this->render('stagiaire/delete.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }

    /**
     * @Route("/{id}", name="stagiaire_show")
     */
    public function show(Stagiaire $stagiaire): Response
    {
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }
}
