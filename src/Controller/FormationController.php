<?php

namespace App\Controller;

use App\Entity\Formation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/formation")
 */
class FormationController extends AbstractController
{
        /**
     * @Route("/", name="formation_index")
     */
    public function index(): Response
    {
        $formations = $this->getDoctrine()
            ->getRepository(Formation::class)
            ->findBy([], ['nom' => 'ASC']);

        return $this->render('formation/index.html.twig', [
            'formations' => $formations
        ]);
    }
    /**
     * @Route("/new", name="formation_add")
     * @Route("/edit/{id}", name="formation_edit")
     */
    public function new(Request $request, Formation $formation = null): Response
    {
        if(!$formation){
            $formation = new Formation();
        }

        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
            $formation = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('formation');
        }

        return $this->render('formation/new.html.twig', [
            'formAddFormation' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="formation_delete")
     */
    public function delete(Formation $formation): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($formation);
        $entityManager->flush();

        return $this->redirectToRoute('formation');
    }

    /**
     * @Route("/{id}", name="formation_show")
     */
    public function show(): Response
    {
        return $this->render('formation/show.html.twig', [
            'formation' => 'formation',
        ]);
    }
}
