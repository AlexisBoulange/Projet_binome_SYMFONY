<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgrammerController extends AbstractController
{
    /**
     * @Route("/programmer", name="programmer_index")
     */
    public function index(): Response
    {
        return $this->render('programmer/index.html.twig', [
            'controller_name' => 'ProgrammerController',
        ]);
    }
}
