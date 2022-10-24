<?php

namespace App\Service\CategoryService;

use App\Repository\CategoryRepository;

class CategoryFinder
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
     * @return array|null
     */
    public function findAll(): ?array
    {
        return $this->categoryRepository->findAll();
    }
}