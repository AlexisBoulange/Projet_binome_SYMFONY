<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/categorie")
 * @IsGranted("ROLE_USER")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="categorie_index")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Categorie::class)
            ->findBy([], ['nom' => 'ASC']);

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories
        ]);
    }
    /**
     * @Route("/new", name="categorie_add")
     * @Route("/edit/{id}", name="categorie_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, Categorie $categorie = null): Response
    {
        if(!$categorie){
            $categorie = new Categorie();
        }

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
            $categorie = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/new.html.twig', [
            'formAddCategorie' => $form->createView(),
            'categorie' => $categorie,
            'editMode' => $categorie->getId() !==null
        ]);
    }
/**
     * @Route("/confirm/{id}", name="categorie_confirmation")
     * @IsGranted("ROLE_ADMIN")
    */
    public function showConfirm(Categorie $categorie): Response
    {
        return $this->render('categorie/confirmation.html.twig', [
            'categorie' => $categorie,
        ]);
    }
    /**
     * @Route("/delete/{id}", name="categorie_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Categorie $categorie): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($categorie);
        $entityManager->flush();

       //message add flash de confirmation
       $this->addFlash('success', 'La cat??gorie a bien ??t?? supprim??e!');

       return $this->redirectToRoute('categorie_index');
    }

    /**
     * @Route("/{id}", name="categorie_show")
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }
}
