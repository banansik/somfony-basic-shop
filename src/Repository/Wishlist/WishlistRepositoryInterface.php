<?php

namespace App\Repository\Wishlist;

interface WishlistRepositoryInterface
{
    /**
     * @return array|null
     * (
     * [id] => {
     * [id] => int,
     * ['name'] => string,
     * [...]
     * }
     * )
     */
    public function findAll(): ?array;
}