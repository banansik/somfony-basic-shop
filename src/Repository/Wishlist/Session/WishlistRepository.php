<?php

namespace App\Repository\Wishlist\Session;

use App\Repository\Wishlist\WishlistRepositoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WishlistRepository implements WishlistRepositoryInterface
{
    public const SESSION_NAME = 'products';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * WishlistRepository constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->session->get(self::SESSION_NAME, []);
    }
}