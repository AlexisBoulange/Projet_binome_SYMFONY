<?php

namespace App\Controller;

use App\Entity\Module;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/module")
 */
class ModuleController extends AbstractController
{
    /**
     * @Route("/", name="module_index")
     */
    public function index(): Response
    {
        $modules = $this->getDoctrine()
            ->getRepository(Module::class)
            ->findBy([], ['libelle' => 'ASC']);

        return $this->render('module/index.html.twig', [
            'modules' => $modules
        ]);
    }
    /**
     * @Route("/new", name="module_add")
     * @Route("/edit/{id}", name="module_edit")
     */
    public function new(Request $request, Module $module = null): Response
    {
        if(!$module){
            $module = new Module();
        }

        $form = $this->createForm(ModuleType::class, $module);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
            $module = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($module);
            $entityManager->flush();

            return $this->redirectToRoute('module');
        }

        return $this->render('module/new.html.twig', [
            'formAddmodule' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="module_show")
     */
    public function show(): Response
    {
        return $this->render('module/show.html.twig', [
            'module' => 'module',
        ]);
    }
}
