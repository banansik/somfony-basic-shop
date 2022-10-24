<?php

namespace App\Service\Category;

use App\Entity\Category;
use App\Repository\Category\CategoryRepositoryInterface;

class CategoryCreator
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * CategoryCreator constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param Category $category
     */
    public function execute(Category $category): void
    {
        $this->categoryRepository->save($category);
    }
}