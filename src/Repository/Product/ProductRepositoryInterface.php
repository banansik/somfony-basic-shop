<?php

namespace App\Repository\Product;

use App\Entity\Product;

interface ProductRepositoryInterface
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

    /**
     * @param array $ids
     * @return array of products with specific $ids
     * (
     * [] => [
     *      [id] => int,
     *      ['name'] => string,
     *      [...]
     * ]
     * )
     */
    public function findProductsByIds(array $ids): array;

    /**
     * @param array $names
     * @return array of products with specific $names
     * (
     * [] => [
     *      [id] => int,
     *      ['name'] => string,
     *      [...]
     * ]
     * )
     */
    public function findProductsByNames(array $names): array;

    /**
     * @param array $names
     * @return array of names of product with specific $id
     * (
     * [] => [
     *      [id] => int,
     *      ['name'] => string,
     *      [...]
     * ]
     * )
     */
    public function findProductsNames(array $names): array;

    public function findOneBy(array $criteria);
}