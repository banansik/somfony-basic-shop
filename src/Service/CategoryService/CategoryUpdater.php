<?php

namespace App\Service\CategoryService;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryUpdater
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
    public function update(Category $category): void
    {
        $this->categoryRepository->save($category);
    }
}