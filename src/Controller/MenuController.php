<?php

namespace App\Controller;

use App\Repository\Category\CategoryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends AbstractController
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * MenuController constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return Response
     */
    public function createMenu(): Response
    {
        return $this->render(
            '/navbar/_dropdown_menu.html.twig',
            ['categories' => $this->categoryRepository->findAll()]
        );
    }
}