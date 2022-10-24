<?php

namespace App\Controller;

use App\Exception\Wishlist\AlreadyInWishlistException;
use App\Exception\Wishlist\LimitReachedException;
use App\Repository\Product\Mysql\ProductRepository;
use App\Repository\Wishlist\WishlistRepositoryInterface;
use App\Service\Wishlist\Session\WishlistCreator;
use App\Service\Wishlist\WishlistCreatorInterface;
use App\Service\Wishlist\WishlistRemoverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wishlist")
 */
class WishListController extends AbstractController
{
    /**
     * @var WishlistRemoverInterface
     */
    private $wishlistRemover;

    /**
     * WishListController constructor.
     * @param WishlistRemoverInterface $wishlistRemover
     */
    public function __construct(WishlistRemoverInterface $wishlistRemover)
    {
        $this->wishlistRemover = $wishlistRemover;
    }

    /**
     * @Route( "/", methods={"GET"}, name="wishlist_index")
     * @param WishlistRepositoryInterface $wishlistRepository
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function index(WishlistRepositoryInterface $wishlistRepository, ProductRepository $productRepository): Response
    {
        $productIds = $wishlistRepository->findAll();

        return $this->render(
            'wishlist/index.html.twig',
            ['products' => $productRepository->findProductsByIds($productIds)]
        );
    }

    /**
     * @Route("/{id}", name="wishlist_add", methods={"GET"}, requirements={"id": "[1-9]\d*"})
     * @param int $id
     * @param WishlistCreatorInterface $wishlistCreator
     * @return Response
     */
    public function add(int $id, WishlistCreatorInterface $wishlistCreator): Response
    {
        try {
            $wishlistCreator->add($id);
            $this->addFlash('success', 'Product added to your wishlist!');
        } catch (AlreadyInWishlistException $alreadyInWishlistException) {
            $this->addFlash('danger', 'Product already on your wishlist');
        } catch (LimitReachedException $limitReachedException) {
            $this->addFlash('danger', 'Limit of ' . WishlistCreator::PRODUCTS_LIMIT . ' products on wishlist reached!');
        } catch (\Exception $exception) {
            $this->addFlash('danger', 'Sorry, we cannot add this product to your wishlist');
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/remove/{id}", name="wishlist_remove", requirements={"id": "[1-9]\d*"})
     * @param int $id
     */
    public function remove(int $id): Response
    {
        try {
            $this->wishlistRemover->removeById($id);
            $this->addFlash('success', 'Success, product removed from you wishlist!');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Sorry, we cannot remove product from your wishlist');
        }

        return $this->redirectToRoute('wishlist_index');
    }

    /**
     * @Route("/remove-all", name="wishlist_remove_all", methods={"GET"})
     */
    public function removeAll(): Response
    {
        try {
            $this->wishlistRemover->removeAll();
            $this->addFlash('success', 'Success, products removed from you wishlist!');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Sorry, we cannot remove products from your wishlist');
        }

        return $this->redirectToRoute('wishlist_index');
    }
}