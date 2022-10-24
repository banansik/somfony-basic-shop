<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    /**
     * @return Response
     * @Route("/welcome", name="welcome")
     */
    public function index()
    {
        return $this->render('/welcome/index.html.twig');
    }
}