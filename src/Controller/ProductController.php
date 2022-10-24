<?php

namespace App\Controller;

use App\Entity\Product;
use App\Exception\Image\MainImageLimitException;
use App\Form\ProductType;
use App\Repository\Product\ProductRepositoryInterface;
use App\Service\Image\ImageSaverInterface;
use App\Service\Product\Mysql\ProductFinder;
use App\Service\Product\ProductCreatorInterface;
use App\Service\Product\ProductRemoverInterface;
use App\Service\Product\ProductSaverInterface;
use App\Service\Product\ProductUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 *
 * @Route("/products")
 */
class ProductController extends AbstractController
{
    /**
     * @var ProductSaverInterface
     */
    private $productSaverInterface;

    /**
     * ProductController constructor.
     * @param ProductSaverInterface $productSaverInterface
     */
    public function __construct(ProductSaverInterface $productSaverInterface)
    {
        $this->productSaverInterface = $productSaverInterface;
    }

    /**
     * @param ProductRepositoryInterface $productRepository
     * @return Response
     *
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepositoryInterface $productRepository): Response
    {
        return $this->render(
            'product/index.html.twig',
            ['products' => $productRepository->findAll()]
        );
    }

    /**
     * @param Product $product
     *
     * @return Response
     *
     * @Route("/{id}", methods={"GET"}, name="product_show", requirements={"id": "[1-9]\d*"})
     */
    public function show(Product $product): Response
    {
        $form = $this->createForm(FormType::class, $product, ['method' => 'DELETE']);

        return $this->render(
            'product/show.html.twig',
            [
                'form' => $form->createView(),
                'product' => $product,
            ]
        );
    }

    /**
     * @param Request $request
     * @param ProductSaverInterface $productCreator
     * @return Response
     *
     * @Route("/create", methods={"GET","POST"}, name="product_create")
     */
    public function create(Request $request, ProductCreatorInterface $productCreator, ImageSaverInterface $imageSaver): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $images = $form->get('images')->getData()->getValues();

                $imageSaver->execute($images);

                $this->productSaverInterface->execute($form->getData());
                return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
            } catch (MainImageLimitException $mainImageLimitException) {
                $this->addFlash('danger', 'You can choose only one main image');
            } catch (\Exception $exception) {
                $this->addFlash('danger', $exception->getMessage());
            }
        }

        return $this->render(
            'product/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     * @param Product $product
     * @param ProductUpdaterInterface $productUpdater
     * @return Response
     *
     * @Route("/{id}/edit", methods={"GET","PUT","POST"}, name="product_update", requirements={"id": "[1-9]\d*"})
     */
    public function update(Request $request, Product $product, ProductUpdaterInterface $productUpdater, ImageSaverInterface $imageSaver): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $images = $form->get('images')->getData()->getValues();

                $imageSaver->execute($images);

                $this->productSaverInterface->execute($product);

                $this->addFlash('success', 'Product updated!');

                return $this->redirectToRoute('product_index');
            } catch (MainImageLimitException $mainImageLimitException) {
                $this->addFlash('danger', 'You can choose only one main image');
            } catch (\Exception $exception) {
                $this->addFlash('danger', $exception->getMessage());
            }
        }

        return $this->render(
            'product/update.html.twig',
            [
                'form' => $form->createView(),
                'product' => $product,
            ]
        );
    }

    /**
     * @param Product $product
     * @param ProductRemoverInterface $productRemover
     * @return Response
     *
     * @Route("/{id}/delete/", name="product_delete", methods={"DELETE"}, requirements={"id": "[1-9]\d*"})
     */
    public function delete(Product $product, ProductRemoverInterface $productRemover): Response
    {
        try {
            $productRemover->execute($product);

            $this->addFlash('success', 'Product deleted!');
        } catch (\Exception $exception) {
            $this->addFlash('danger', 'Error, cannot delete this product.');
        }

        return $this->redirectToRoute('product_index');
    }
}