<?php

namespace App\Service\CategoryService;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryRemover
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryFinder constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param Category $category
     */
    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }
}