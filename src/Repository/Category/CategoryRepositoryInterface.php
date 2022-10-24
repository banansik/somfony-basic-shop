<?php
declare(strict_types=1);

namespace App\Repository\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return array|null
     * (
     * [] => [
     *      [id] => int,
     *      ['name'] => string,
     *      [...]
     * ]
     * )
     */
    public function findAll();

    public function findBy(array $criteria);

    public function findCategoriesByIdByIds(array $ids): array;
}