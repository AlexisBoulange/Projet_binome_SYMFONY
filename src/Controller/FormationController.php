<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_ADMIN")
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

            return $this->redirectToRoute('formation_index');
        }

        return $this->render('formation/new.html.twig', [
            'formAddFormation' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="formation_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Formation $formation): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($formation);
        $entityManager->flush();

        return $this->redirectToRoute('formation_index');
    }

    /**
     * @Route("/{id}", name="formation_show")
     */
    public function show(Formation $formation): Response
    {
        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
        ]);
    }
}
