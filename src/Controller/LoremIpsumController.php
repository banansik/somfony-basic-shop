<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoremIpsumController extends AbstractController
{
    /**
     * @return Response
     * @Route("/lorem-ipsum", name="lorem_ipsum")
     */
    public function index(): Response
    {
        return $this->render('loremipsum/index.html.twig');
    }
}