<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\Category\CategoryRepositoryInterface;
use App\Service\Category\CategoryCreator;
use App\Service\Category\CategoryFinder;
use App\Service\Category\CategoryRemover;
use App\Service\Category\CategoryUpdater;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 *
 * @Route("/categories")
 */
class CategoryController extends AbstractController
{
    /**
     * @var CategoryCreator
     */
    private $categoryCreator;

    /**
     * @var CategoryRemover
     */
   private $categoryRemover;

    /**
     * @var CategoryUpdater
     */
   private $categoryUpdater;

    /**
     * CategoryController constructor.
     * @param CategoryCreator $categoryCreator
     * @param CategoryRemover $categoryRemover
     * @param CategoryUpdater $categoryUpdater
     */
    public function __construct(
        CategoryCreator $categoryCreator,
        CategoryRemover $categoryRemover,
        CategoryUpdater $categoryUpdater
    ) {
        $this->categoryCreator = $categoryCreator;
        $this->categoryRemover = $categoryRemover;
        $this->categoryUpdater = $categoryUpdater;
    }

    /**
     * @return Response
     *
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepositoryInterface $categoryRepository): Response
    {
        return $this->render(
            'category/index.html.twig',
            ['categories' => $categoryRepository->findAll()]
        );
    }

    /**
     * @param Category $category
     *
     * @return Response
     *
     * @Route("/{id}", methods={"GET"}, name="category_show", requirements={"id": "[1-9]\d*"},)
     */
    public function show(Category $category): Response
    {
        $form = $this->createForm(FormType::class, $category, ['method' => "DELETE"]);

        return $this->render(
            'category/show.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/create", methods={"GET","POST"}, name="category_create")
     */
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->categoryCreator->execute($category);

                $this->addFlash('success', 'Category created!');

                return $this->redirectToRoute('category_show', ['id' => $category->getId()]);
            } catch (\Exception $exception) {
                $this->addFlash('danger', 'Error, cannot create new category.');
            }
        }

        return $this->render(
            'category/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     * @param Category $category
     *
     * @return Response
     *
     * @Route("/{id}/edit", methods={"GET","PUT","POST"}, name="category_update", requirements={"id": "[1-9]\d*"})
     */
    public function update(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->categoryUpdater->execute($category);

                $this->addFlash('success', 'Category updated!');
            } catch (\Exception $exception) {
                $this->addFlash('danger', 'Error, cannot update this category.');
            }

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/update.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }

    /**
     * @param Category $category
     *
     * @return Response
     *
     * @Route("/{id}/delete", methods={"DELETE"}, name="category_delete", requirements={"id": "[1-9]\d*"} )
     */
    public function delete(Category $category): Response
    {
        try {
            $this->categoryRemover->execute($category);

            $this->addFlash('success', 'Category deleted!');
        } catch (\Exception $exception) {
            $this->addFlash('danger', 'Error, cannot delete this category.');
        }

        return $this->redirectToRoute('category_index');
    }
}